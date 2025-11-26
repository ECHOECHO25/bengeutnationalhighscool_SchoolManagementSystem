<?php

namespace App\Livewire\Student;
use App\Models\Exam;
use App\Models\SubjectGrade;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class GradeRecord extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $student_id;

    public function mount(){
        $this->student_id = auth()->user()->student->id;
    }
     public function table(Table $table): Table
    {
        return $table
            ->query(SubjectGrade::query()->whereHas('studentGrades', function($query){
                $query->where('student_id', $this->student_id);
            }))
            ->columns([
                TextColumn::make('name')->label('NAME')->searchable(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                 Action::make('download')->label('Download')->button()
            ])
            ->bulkActions([
                // ...
            ]);
    }


    public function render()
    {
        return view('livewire.student.grade-record',[
            'exams' => Exam::where('department', auth()->user()->student->gradeLevel->department)->get(),
        ]);

    }
}
