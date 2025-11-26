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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use function Flasher\SweetAlert\Prime\sweetalert;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EnrollStudent extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $student;
    public $grade_level_id;
    public $department;

    public $semester, $track, $strand, $classroom_id;

    public function updatedGradeLevelId()
    {
        $gradeLevel = GradeLevel::where('id', $this->grade_level_id)->first();
        if ($gradeLevel) {
            $this->department = $gradeLevel->department;
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(StudentInformation::query()->whereDoesntHave('student', function($record){
                return $record->where('school_year_id', SchoolYear::where('is_active', 1)->first()->id);
            })) // all records by default
            ->columns([
                TextColumn::make('lrn')->label('LRN')->searchable(),
                TextColumn::make('firstname')
                    ->label('FULLNAME')
                    ->searchable()
                    ->formatStateUsing(fn(string $state, StudentInformation $record): string =>
                        "{$record->lastname}, {$record->firstname}"
                    ),
                TextColumn::make('gradeLevel.name')->label('GRADE LEVEL'),
                TextColumn::make('gradeLevel.department')->label('DEPARTMENT'),
            ])
            ->filters([
                SelectFilter::make('grade_level_id')
                    ->label('Grade Level')
                    ->options(

                        GradeLevel::all()->pluck('name', 'id')->toArray()
                    ),
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
            ->bulkActions([/* ... */]);
    }

    public function enrollNow()
    {
        sleep(2);
        DB::beginTransaction();
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

        $studentClassroom = StudentClassroom::create([
            'student_id'   => $stud->id,
            'classroom_id' => $this->classroom_id,
        ]);
        DB::commit();

        sweetalert()->success('Student Enrolled Successfully!');
        return auth()->user()->role == 'admin' ? redirect(route('admin.student')) : redirect()->route('encoder.dashboard');
    }

    public function render()
    {
        return view('livewire.admin.enroll-student', [
            'grade_levels' => GradeLevel::all(),
            'classrooms'   => Classroom::where('grade_level_id', $this->grade_level_id)->get(),
        ]);
    }
}
