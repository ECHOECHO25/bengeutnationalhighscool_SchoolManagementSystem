<?php

namespace App\Livewire;
use App\Models\StudentClassroom;
use App\Models\TeacherSubject;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class StudentDashboard extends Component implements HasForms, HasTable
{
     use InteractsWithTable;
     use InteractsWithForms;
        public $classroom;

     public function mount(){
        $stud_id = auth()->user()->student->id;
        $this->classroom = StudentClassroom::where('student_id', $stud_id)->first()->classroom_id;
     }

    public function table(Table $table): Table
    {
        return $table
            ->query(TeacherSubject::query()->where('classroom_id', $this->classroom))
            ->columns([
                Stack::make([
                    TextColumn::make('name')->label('NAME')->weight('bold')->size('2xl')->formatStateUsing(
                        fn($record) => strtoupper($record->name)
                    )->searchable(),
                    TextColumn::make('schedule')->label('SCHEDULE')->searchable(),
                    // TextColumn::make('teacher')->label('INSTRUCTOR')->searchable()->formatStateUsing(
                    //     fn($record) => $record->teacher->lastname. ', ' . $record->teacher->firstname
                    // ),
                    TextColumn::make('classroom.id')->label('GRADE LEVEL')->searchable()->formatStateUsing(
                        fn($record) => $record->classroom->gradeLevel->name
                    ),
                    TextColumn::make('classroom')->label('CLASSROOM')->badge()->searchable()->formatStateUsing(
                        fn($record) => $record->classroom->building_number . ' - ' . $record->classroom->section
                    ),
                ]),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ])->contentGrid([
            'md' => 3,
            'xl' => 4,
        ]);
    }

    public function render()
    {
        return view('livewire.student-dashboard');
    }
}
