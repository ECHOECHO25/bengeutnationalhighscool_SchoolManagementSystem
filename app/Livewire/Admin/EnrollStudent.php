<?php
namespace App\Livewire\Admin;

use App\Models\Classroom;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentInformation;
use App\Models\User;
use DB;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use function Flasher\SweetAlert\Prime\sweetalert;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class EnrollStudent extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $student;
    public $grade_level_id;
    public $department;

    public $semester, $track, $strand, $classroom_id;

    // Batch enrollment properties
    public $batchGradeLevelId;
    public $batchDepartment;
    public $batchSemester;
    public $batchTrack;
    public $batchStrand;
    public $batchClassroomId;
    public $selectedStudents = [];

    // Group by last year's classroom
    public $showGroupedModal = false;
    public $groupedStudents = [];
    public $previousSchoolYearId;

    public function updatedGradeLevelId()
    {
        $gradeLevel = GradeLevel::where('id', $this->grade_level_id)->first();
        if ($gradeLevel) {
            $this->department = $gradeLevel->department;
        }
    }

    public function updatedBatchGradeLevelId()
    {
        $gradeLevel = GradeLevel::where('id', $this->batchGradeLevelId)->first();
        if ($gradeLevel) {
            $this->batchDepartment = $gradeLevel->department;
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(StudentInformation::query()->whereDoesntHave('student', function($record){
                return $record->where('school_year_id', SchoolYear::where('is_active', 1)->first()->id);
            }))
            ->columns([
                TextColumn::make('lrn')->label('LRN')->searchable(),
                TextColumn::make('firstname')
                    ->label('FULLNAME')
                    ->searchable()
                    ->formatStateUsing(fn(string $state, StudentInformation $record): string =>
                        "{$record->lastname}, {$record->firstname}"
                    ),
                TextColumn::make('gradeLevel.name')->label('GRADE LEVEL')->sortable(),
                TextColumn::make('gradeLevel.department')->label('DEPARTMENT'),
                TextColumn::make('last_classroom')
                    ->label('LAST CLASSROOM')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains($state, 'New Student') => 'success',
                        str_contains($state, 'N/A') => 'gray',
                        default => 'info',
                    })
                    ->formatStateUsing(function (StudentInformation $record) {
                        // Get previous school year
                        $prevYear = SchoolYear::where('is_active', 0)
                            ->orderBy('id', 'desc')
                            ->first();

                        if (!$prevYear) return 'N/A';

                        // Find student's last enrollment
                        $lastStudent = Student::where('lrn', $record->lrn)
                            ->where('school_year_id', $prevYear->id)
                            ->with('gradeLevel')
                            ->first();

                        if (!$lastStudent) return 'New Student';

                        // Get classroom
                        $classroom = StudentClassroom::where('student_id', $lastStudent->id)
                            ->with('classroom')
                            ->first();

                        $gradeLevel = $lastStudent->gradeLevel ? $lastStudent->gradeLevel->name : 'Unknown';
                        $classroomInfo = $classroom ? $classroom->classroom->section . ' - ' . $classroom->classroom->building_number : 'N/A';

                        return $gradeLevel . ' | ' . $classroomInfo;
                    }),
            ])
            ->defaultSort('lrn', 'asc')
            ->filters([
                SelectFilter::make('grade_level_id')
                    ->label('Grade Level')
                    ->options(
                        GradeLevel::all()->pluck('name', 'id')->toArray()
                    ),
                SelectFilter::make('last_classroom')
                    ->label('Previous Classroom')
                    ->options(function () {
                        $prevYear = SchoolYear::where('is_active', 0)
                            ->orderBy('id', 'desc')
                            ->first();

                        if (!$prevYear) return [];

                        // Get all classrooms that had students last year with their grade levels
                        $classrooms = [];

                        $students = Student::where('school_year_id', $prevYear->id)
                            ->with(['gradeLevel', 'studentClassrooms.classroom'])
                            ->get();

                        foreach ($students as $student) {
                            if ($student->studentClassrooms->isNotEmpty()) {
                                $classroom = $student->studentClassrooms->first()->classroom;
                                $gradeLevel = $student->gradeLevel ? $student->gradeLevel->name : 'Unknown';
                                $key = $gradeLevel . ' | ' . $classroom->section . ' - ' . $classroom->building_number;
                                $classrooms[$key] = $key;
                            }
                        }

                        // Sort and add "New Student" option
                        ksort($classrooms);
                        return array_merge(['New Student' => 'New Student'], $classrooms);
                    })
                    ->query(function ($query, $state) {
                        if (!$state['value']) return $query;

                        $prevYear = SchoolYear::where('is_active', 0)
                            ->orderBy('id', 'desc')
                            ->first();

                        if (!$prevYear) return $query;

                        // Handle "New Student" filter
                        if ($state['value'] === 'New Student') {
                            return $query->whereDoesntHave('student', function($q) use ($prevYear) {
                                $q->where('school_year_id', $prevYear->id);
                            });
                        }

                        // Parse the filter value (format: "Grade X | Section - Room")
                        $parts = explode(' | ', $state['value']);
                        if (count($parts) !== 2) return $query;

                        $gradeLevelName = $parts[0];
                        $classroomName = $parts[1];

                        // Filter by specific classroom and grade level
                        return $query->whereHas('student', function($q) use ($prevYear, $gradeLevelName, $classroomName) {
                            $q->where('school_year_id', $prevYear->id)
                              ->whereHas('gradeLevel', function($glQ) use ($gradeLevelName) {
                                  $glQ->where('name', $gradeLevelName);
                              })
                              ->whereHas('studentClassrooms', function($scQ) use ($classroomName) {
                                  $scQ->whereHas('classroom', function($classQ) use ($classroomName) {
                                      $classQ->whereRaw("CONCAT(section, ' - ', building_number) = ?", [$classroomName]);
                                  });
                              });
                        });
                    }),
            ])->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Action::make('enroll')->label('Enroll Now')->button()->color('success')->icon('heroicon-s-check-circle')->iconPosition('after')->size('sm')
                    ->action(
                        function ($record) {
                            sleep(1);
                            $this->student        = $record;
                            $this->grade_level_id = $record->grade_level_id;
                            $this->department     = $record->gradeLevel->department;
                            $this->dispatch('open-modal', id: 'enroll-student');
                        }
                    ),
                Action::make('edit')->label('Edit')->button()->color('warning')->icon('heroicon-s-pencil')->iconPosition('after')->size('sm')
                    ->url(fn($record) => route('admin.edit-student-data', encrypt($record->id))),
            ])
            ->bulkActions([
                BulkAction::make('batch_enroll')
                    ->label('Batch Enroll')
                    ->icon('heroicon-o-user-group')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $this->selectedStudents = $records->toArray();
                        $this->dispatch('open-modal', id: 'batch-enroll-modal');
                    })
                    ->deselectRecordsAfterCompletion(),

                BulkAction::make('batch_enroll_grouped')
                    ->label('Batch Enroll (Grouped by Last Classroom)')
                    ->icon('heroicon-o-squares-2x2')
                    ->color('info')
                    ->action(function (Collection $records) {
                        $this->groupStudentsByLastClassroom($records);
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public function groupStudentsByLastClassroom(Collection $records)
    {
        // Get previous school year
        $this->previousSchoolYearId = SchoolYear::where('is_active', 0)
            ->orderBy('id', 'desc')
            ->first();

        if (!$this->previousSchoolYearId) {
            sweetalert()->warning('No previous school year found!');
            return;
        }

        $grouped = [];

        foreach ($records as $record) {
            $studentInfo = StudentInformation::find($record->id);

            // Find student's last enrollment
            $lastStudent = Student::where('lrn', $studentInfo->lrn)
                ->where('school_year_id', $this->previousSchoolYearId->id)
                ->first();

            if ($lastStudent) {
                // Get classroom
                $studentClassroom = StudentClassroom::where('student_id', $lastStudent->id)
                    ->with('classroom')
                    ->first();

                if ($studentClassroom) {
                    $classroomKey = $studentClassroom->classroom_id;
                    $classroomName = $studentClassroom->classroom->section . ' - ' . $studentClassroom->classroom->building_number;

                    if (!isset($grouped[$classroomKey])) {
                        $grouped[$classroomKey] = [
                            'classroom_name' => $classroomName,
                            'classroom_id' => $classroomKey,
                            'students' => [],
                            'grade_level_id' => null,
                            'classroom_id_new' => null,
                        ];
                    }

                    $grouped[$classroomKey]['students'][] = $studentInfo->toArray();
                } else {
                    // No classroom - group as "New Students"
                    if (!isset($grouped['new'])) {
                        $grouped['new'] = [
                            'classroom_name' => 'New Students',
                            'classroom_id' => null,
                            'students' => [],
                            'grade_level_id' => null,
                            'classroom_id_new' => null,
                        ];
                    }
                    $grouped['new']['students'][] = $studentInfo->toArray();
                }
            } else {
                // Student not found in previous year - group as "New Students"
                if (!isset($grouped['new'])) {
                    $grouped['new'] = [
                        'classroom_name' => 'New Students',
                        'classroom_id' => null,
                        'students' => [],
                        'grade_level_id' => null,
                        'classroom_id_new' => null,
                    ];
                }
                $grouped['new']['students'][] = $studentInfo->toArray();
            }
        }

        $this->groupedStudents = array_values($grouped);
        $this->dispatch('open-modal', id: 'grouped-enroll-modal');
    }

    public function enrollGroupedStudents()
    {
        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($this->groupedStudents as $group) {
            // Skip if no grade level or classroom selected
            if (!$group['grade_level_id'] || !$group['classroom_id_new']) {
                $failedCount += count($group['students']);
                $errors[] = "Group '{$group['classroom_name']}': Missing grade level or classroom";
                continue;
            }

            // Get grade level to check if Senior High
            $gradeLevel = GradeLevel::find($group['grade_level_id']);
            $isSeniorHigh = $gradeLevel->department == 'Senior High School';

            foreach ($group['students'] as $studentData) {
                DB::beginTransaction();

                try {
                    $studentInfo = StudentInformation::find($studentData['id']);

                    if (!$studentInfo) {
                        $failedCount++;
                        continue;
                    }

                    // Check if user exists
                    $email = strtolower($studentInfo->firstname) . strtolower($studentInfo->lastname) . '@bnhs.edu.ph';
                    $existingUser = User::where('email', $email)->first();

                    if ($existingUser) {
                        $user = $existingUser;
                    } else {
                        $user = User::create([
                            'name'     => $studentInfo->firstname . ' ' . $studentInfo->lastname,
                            'email'    => $email,
                            'password' => bcrypt($studentInfo->lrn),
                            'role'     => 'student',
                        ]);
                    }

                    $stud = Student::create([
                        'lrn'                    => $studentInfo->lrn,
                        'firstname'              => $studentInfo->firstname,
                        'middlename'             => $studentInfo->middlename,
                        'lastname'               => $studentInfo->lastname,
                        'is_senior_high'         => $isSeniorHigh ? 1 : 0,
                        'track'                  => $isSeniorHigh ? ($group['track'] ?? null) : null,
                        'strand'                 => $isSeniorHigh ? ($group['strand'] ?? null) : null,
                        'school_year_id'         => SchoolYear::where('is_active', true)->first()->id,
                        'grade_level_id'         => $group['grade_level_id'],
                        'user_id'                => $user->id,
                        'student_information_id' => $studentInfo->id,
                    ]);

                    StudentClassroom::create([
                        'student_id'   => $stud->id,
                        'classroom_id' => $group['classroom_id_new'],
                    ]);

                    DB::commit();
                    $successCount++;

                } catch (\Exception $e) {
                    DB::rollBack();
                    $failedCount++;
                    $errors[] = "Failed: {$studentInfo->firstname} {$studentInfo->lastname} - " . $e->getMessage();
                }
            }
        }

        // Show results
        if ($successCount > 0) {
            sweetalert()->success("{$successCount} student(s) enrolled successfully!");
        }

        if ($failedCount > 0) {
            sweetalert()->warning("{$failedCount} student(s) failed. Check logs.");
            \Log::warning('Grouped enrollment errors:', $errors);
        }

        $this->reset(['groupedStudents']);
        $this->dispatch('close-modal', id: 'grouped-enroll-modal');
    }

    public function enrollNow()
    {
        sleep(2);
        DB::beginTransaction();

        try {
            $this->validate([
                'grade_level_id' => 'required',
                'classroom_id'   => 'required',
                'semester'       => $this->department == 'Senior High School' ? 'required' : '',
                'track'          => $this->department == 'Senior High School' ? 'required' : '',
                'strand'         => $this->department == 'Senior High School' ? 'required' : '',
            ]);

            $user = User::create([
                'name'     => $this->student->firstname . ' ' . $this->student->lastname,
                'email'    => strtolower($this->student->firstname) . '' . strtolower($this->student->lastname) . '@bnhs.edu.ph',
                'password' => bcrypt($this->student->lrn),
                'role'     => 'student',
            ]);

            $stud = Student::create([
                'lrn'                    => $this->student->lrn,
                'firstname'              => $this->student->firstname,
                'middlename'             => $this->student->middlename,
                'lastname'               => $this->student->lastname,
                'is_senior_high'         => $this->department == 'Senior High School' ? 1 : 0,
                'track'                  => $this->department == 'Senior High School' ? $this->track : null,
                'strand'                 => $this->department == 'Senior High School' ? $this->strand : null,
                'school_year_id'         => SchoolYear::where('is_active', true)->first()->id,
                'grade_level_id'         => $this->grade_level_id,
                'user_id'                => $user->id,
                'student_information_id' => $this->student->id,
            ]);

            StudentClassroom::create([
                'student_id'   => $stud->id,
                'classroom_id' => $this->classroom_id,
            ]);

            DB::commit();

            sweetalert()->success('Student Enrolled Successfully!');
            $this->dispatch('close-modal', id: 'enroll-student');

        } catch (\Exception $e) {
            DB::rollBack();
            sweetalert()->error('Enrollment failed: ' . $e->getMessage());
        }
    }

    public function batchEnrollNow()
    {
        sleep(2);

        $this->validate([
            'batchGradeLevelId' => 'required',
            'batchClassroomId'  => 'required',
            'batchSemester'     => $this->batchDepartment == 'Senior High School' ? 'required' : '',
            'batchTrack'        => $this->batchDepartment == 'Senior High School' ? 'required' : '',
            'batchStrand'       => $this->batchDepartment == 'Senior High School' ? 'required' : '',
        ]);

        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($this->selectedStudents as $studentData) {
            DB::beginTransaction();

            try {
                $studentInfo = StudentInformation::find($studentData['id']);

                if (!$studentInfo) {
                    $failedCount++;
                    $errors[] = "Student ID {$studentData['id']} not found";
                    continue;
                }

                $email = strtolower($studentInfo->firstname) . strtolower($studentInfo->lastname) . '@bnhs.edu.ph';
                $existingUser = User::where('email', $email)->first();

                if ($existingUser) {
                    $user = $existingUser;
                } else {
                    $user = User::create([
                        'name'     => $studentInfo->firstname . ' ' . $studentInfo->lastname,
                        'email'    => $email,
                        'password' => bcrypt($studentInfo->lrn),
                        'role'     => 'student',
                    ]);
                }

                $stud = Student::create([
                    'lrn'                    => $studentInfo->lrn,
                    'firstname'              => $studentInfo->firstname,
                    'middlename'             => $studentInfo->middlename,
                    'lastname'               => $studentInfo->lastname,
                    'is_senior_high'         => $this->batchDepartment == 'Senior High School' ? 1 : 0,
                    'track'                  => $this->batchDepartment == 'Senior High School' ? $this->batchTrack : null,
                    'strand'                 => $this->batchDepartment == 'Senior High School' ? $this->batchStrand : null,
                    'school_year_id'         => SchoolYear::where('is_active', true)->first()->id,
                    'grade_level_id'         => $this->batchGradeLevelId,
                    'user_id'                => $user->id,
                    'student_information_id' => $studentInfo->id,
                ]);

                StudentClassroom::create([
                    'student_id'   => $stud->id,
                    'classroom_id' => $this->batchClassroomId,
                ]);

                DB::commit();
                $successCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $failedCount++;
                $errors[] = "Failed to enroll {$studentInfo->firstname} {$studentInfo->lastname}: " . $e->getMessage();
            }
        }

        if ($successCount > 0) {
            sweetalert()->success("{$successCount} student(s) enrolled successfully!");
        }

        if ($failedCount > 0) {
            sweetalert()->warning("{$failedCount} student(s) failed to enroll. Check logs for details.");
            \Log::warning('Batch enrollment errors:', $errors);
        }

        $this->reset(['selectedStudents', 'batchGradeLevelId', 'batchDepartment', 'batchSemester', 'batchTrack', 'batchStrand', 'batchClassroomId']);
        $this->dispatch('close-modal', id: 'batch-enroll-modal');
    }

    public function render()
    {
        return view('livewire.admin.enroll-student', [
            'grade_levels' => GradeLevel::all(),
            'classrooms'   => Classroom::where('grade_level_id', $this->grade_level_id)->get(),
            'batch_classrooms' => Classroom::where('grade_level_id', $this->batchGradeLevelId)->get(),
        ]);
    }
}
