<?php
namespace App\Livewire\Teacher;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentClassroom;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TeacherStudent extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $students;

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Student::query()
                    ->when(! empty($this->students), function ($q) {
                        $q->whereIn('id', $this->students);
                    })
                    ->when(empty($this->students), function ($q) {
                        $q->whereRaw('1 = 0'); 
                    });
            })
            ->columns([
                Stack::make([
                    ViewColumn::make('status')->view('filament.tables.avatar'),
                    TextColumn::make('firstname')->formatStateUsing(
                        fn($record) => $record->lastname . ', ' . $record->firstname
                    )->searchable(['lastname', 'firstname']),
                    TextColumn::make('gradeLevel.name')->formatStateUsing(
                        fn($record) => $record->gradeLevel->name . ' - ' . $record->StudentClassrooms->first()->classroom->section
                    ),

                ]),

            ])
            ->filters([
                // ...
            ])
            ->actions([
                EditAction::make('edit')->color('success')->badge(),
                Action::make('view')->label('View Record')->badge()->color('warning')->icon('heroicon-s-eye'),

            ])
            ->bulkActions([
                // ...
            ])->contentGrid([
            'md' => 4,
            'xl' => 5,
        ]);
    }

    public function render()
    {
        $classroom_id   = auth()->user()->teacher->classrooms->first()->id;
        $this->students = StudentClassroom::where('classroom_id', $classroom_id)->pluck('student_id')->toArray();
        return view('livewire.teacher.teacher-student', [
            'classroom' => Classroom::where('teacher_id', auth()->user()->teacher->id)->first(),
        ]);
    }
}
