<?php

namespace App\Livewire\Student;
use App\Models\Attendance;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MyAttendance extends Component implements HasForms, HasTable
{
     use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Attendance::query()->where('student_id', auth()->user()->student->id)->orderBy('date', 'desc'))
            ->columns([
                TextColumn::make('date')->label('DATE')->date(),
                TextColumn::make('teacher_subject_id')->label('SUBJECT & INSTRUCTOR')->searchable()->formatStateUsing(
                    fn($record) => strtoupper($record->teacherSubject->name). ' - ' . strtoupper($record->teacherSubject->teacher->lastname . ', ' . $record->teacherSubject->teacher->firstname)
                ),
                 TextColumn::make('time_in')->label('TIME')->date('h:i A')->searchable(),
                TextColumn::make('status')->label('STATUS')->searchable()->badge()->icon('heroicon-s-square-2-stack')->color(fn(string $state): string => match ($state) {
                    'Absent'     => 'danger',
                    'Late' => 'warning',
                    'Cutting' => 'primary',
                }),
                 TextColumn::make('student')->label('STUDENT')->searchable()->formatStateUsing(
                    fn($record) => $record->student->firstname . ' ' . $record->student->lastname
                ),

            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.student.my-attendance');
    }
}
