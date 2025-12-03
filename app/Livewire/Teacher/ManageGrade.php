<?php
namespace App\Livewire\Teacher;

use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentGrade;
use App\Models\SubjectGrade;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Imports\StudentGradesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GradeTemplateExport;


class ManageGrade extends Component
{
    public $grade_id;
    public $grade;
    public $classroom;
    public $studs = [];
    public $grades = [];
    public $hasChanges = false;
    use WithFileUploads;
    public $importFile;

    public function mount()
    {
        $this->grade_id = decrypt(request('id'));
        $this->grade = SubjectGrade::where('id', $this->grade_id)->first();

        $this->classroom = $this->grade->teacherSubject->classroom->id;
        $this->studs = StudentClassroom::where('classroom_id', $this->classroom)->pluck('student_id')->toArray();

        // Load existing grades
        $this->loadGrades();
    }

    public function loadGrades()
    {
        $students = Student::whereIn('id', $this->studs)->get();

        foreach ($students as $student) {
            $existingGrade = StudentGrade::where('student_id', $student->id)
                ->where('subject_grade_id', $this->grade_id)
                ->first();

            $this->grades[$student->id] = [
                'student_id' => $student->id,
                'grade' => $existingGrade ? $existingGrade->grade : '',
                'existing_id' => $existingGrade ? $existingGrade->id : null,
            ];
        }
    }

    public function downloadTemplate()
{
    return Excel::download(
        new GradeTemplateExport($this->classroom),
        'grade_template.xlsx'
    );
}


    public function updatedGrades()
    {
        $this->hasChanges = true;
    }

    public function submitGrade()
    {
        sleep(1);

        $savedCount = 0;
        $updatedCount = 0;

        foreach ($this->grades as $studentId => $data) {
            // Skip if grade is empty
            if (empty($data['grade']) || $data['grade'] === '') {
                continue;
            }

            // Validate grade (0-100)
            if ($data['grade'] < 0 || $data['grade'] > 100) {
                $this->dispatch('error', message: 'Grade must be between 0 and 100');
                return;
            }

            // Update existing grade
            if ($data['existing_id']) {
                $grade = StudentGrade::find($data['existing_id']);
                if ($grade) {
                    $grade->update(['grade' => $data['grade']]);
                    $updatedCount++;
                }
            } else {
                // Create new grade
                StudentGrade::create([
                    'student_id' => $studentId,
                    'subject_grade_id' => $this->grade_id,
                    'grade' => $data['grade'],
                ]);
                $savedCount++;
            }
        }

        $this->hasChanges = false;
        $this->loadGrades(); // Reload to show as existing grades

        $message = [];
        if ($savedCount > 0) $message[] = "{$savedCount} new grade(s) saved";
        if ($updatedCount > 0) $message[] = "{$updatedCount} grade(s) updated";

        sweetalert()->success(implode(' and ', $message) . ' successfully!');
    }

    public function deleteGrade($studentId)
    {
        $gradeData = $this->grades[$studentId] ?? null;

        if ($gradeData && $gradeData['existing_id']) {
            $grade = StudentGrade::find($gradeData['existing_id']);
            if ($grade) {
                $grade->delete();
                $this->grades[$studentId]['grade'] = '';
                $this->grades[$studentId]['existing_id'] = null;
                sweetalert()->success('Grade deleted successfully!');
            }
        }
    }

public function importGrades()
{
    $this->validate([
        'importFile' => 'required|file|mimes:xlsx,csv',
    ]);

    Excel::import(
        new StudentGradesImport($this->grade_id),
        $this->importFile
    );

    $this->importFile = null;

    $this->loadGrades(); // refresh displayed grades

    $this->dispatch(
        'swal',
        title: 'Grades imported successfully!',
        icon: 'success'
    );
}

    public function clearAll()
    {
        foreach ($this->grades as $studentId => $data) {
            if ($data['existing_id']) {
                $grade = StudentGrade::find($data['existing_id']);
                if ($grade) {
                    $grade->delete();
                }
            }
        }

        $this->loadGrades();
        sweetalert()->success('All grades cleared successfully!');
    }

    public function render()
    {
        return view('livewire.teacher.manage-grade', [
            'students' => Student::whereIn('id', $this->studs)->get(),
        ]);
    }
}
