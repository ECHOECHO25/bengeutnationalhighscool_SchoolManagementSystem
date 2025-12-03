<?php
namespace App\Livewire\Teacher;

use App\Models\Exam;
use App\Models\StudentClassroom;
use App\Models\TeacherSubject;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Livewire\Component;
use Livewire\WithFileUploads;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use App\Models\Student;
use App\Models\StudentGrade;


class SubjectGrade extends Component implements HasForms, HasTable
{
    use WithFileUploads;
    use InteractsWithTable;
    use InteractsWithForms;
    public $subject;

    public $name;
    public $grade;

    public $student_id;
    public $exam_id;

     public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\SubjectGrade::query()->where('teacher_subject_id', $this->subject->id)->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('name'),

            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('manage')->label('Manage Grade')->button()->size('xs')->icon('heroicon-s-arrow-top-right-on-square')->action(
                    function($record){
                        sleep(1);
                        return redirect(route('teacher.manage-grade', ['id' => encrypt($record->id)]));
                    }
                ),
           DeleteAction::make('delete')
                ])
            ->bulkActions([
                // ...
            ]);
    }


    public function mount()
    {
        $this->subject = TeacherSubject::where('id', decrypt(request('id')))->first();
        // dd($this->subject->classroom->gradeLevel);

    }

    public function getExams()
{
    return \App\Models\SubjectGrade::where('teacher_subject_id', $this->subject->id)->get();
}

public function getStudents()
{
    return Student::whereIn(
        'id',
        StudentClassroom::where(
            'classroom_id',
            $this->subject->classroom->id
        )->pluck('student_id')
    )->orderBy('lastname')->get();
}

public function getStudentGrade($studentId, $examId)
{
    return StudentGrade::where('student_id', $studentId)
        ->where('subject_grade_id', $examId)
        ->value('grade');
}

public function getStudentAverage($studentId)
{
    return round(
        StudentGrade::where('student_id', $studentId)
        ->whereHas('subjectGrade', function ($q) {
            $q->where('teacher_subject_id', $this->subject->id);
        })
        ->avg('grade'),
        2
    );
}


    public function uploadGrade()
    {
        sleep(2);
        \App\Models\SubjectGrade::create([
            'teacher_subject_id' => $this->subject->id,
            'name' => $this->name,
            'exam_id' => $this->exam_id,
        ]);

        $this->dispatch('close-modal', id: 'upload-grade');

    }

    public function render()
    {

        return view('livewire.teacher.subject-grade',[
            'students' => StudentClassroom::where('classroom_id', $this->subject->classroom->id)->get(),
            'exams' => Exam::where('department', $this->subject->classroom->gradeLevel->department)->get(),
        ]);
    }
}
