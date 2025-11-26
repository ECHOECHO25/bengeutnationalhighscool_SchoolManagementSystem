<?php
namespace App\Livewire\Teacher;

use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentGrade;
use App\Models\SubjectGrade;
use Livewire\Component;

class ManageGrade extends Component
{
    public $grade_id;
    public $grade;
    public $classroom;
    public $studs  = [];
    public $grades = [];

    public function mount()
    {
        $this->grade_id = decrypt(request('id'));
        $this->grade    = SubjectGrade::where('id', $this->grade_id)->first();

        $this->classroom = $this->grade->teacherSubject->classroom->id;
        $this->studs     = StudentClassroom::where('classroom_id', $this->classroom)->pluck('student_id')->toArray();

    }

    public function setStudentId($id)
{
    $this->grades[$id]['student_id'] = $id;
}

    public function submitGrade(){
        foreach ($this->grades as $key => $value) {
            StudentGrade::create([
                'student_id'      => $value['student_id'],
                'subject_grade_id'=> $this->grade_id,
                'grade'           => $value['grade'],
            ]);
        }
        $this->grades = [];
    }

    public function deleteGrade($id){
        $grade = StudentGrade::where('id', $id)->first();
        if($grade){
            $grade->delete();
        }
    }

    public function render()
    {
        return view('livewire.teacher.manage-grade', [
            'students' => Student::whereIn('id', $this->studs)->get(),
        ]);
    }
}
