<?php
namespace App\Livewire\Teacher;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\TeacherSubject;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SubjectAttendance extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public $subject;
    public $search;

    public function table(Table $table): Table
    {
        return $table
            ->query(Attendance::query()->where('teacher_subject_id', $this->subject->id)->orderBy('date', 'desc'))
            ->columns([
                TextColumn::make('date')->label('DATE')->date()->searchable(),
                TextColumn::make('time_in')->label('TIME')->date('h:i A')->searchable(),
                TextColumn::make('student')->label('STUDENT')->searchable()->formatStateUsing(
                    fn($record) => $record->student->firstname . ' ' . $record->student->lastname
                ),
                TextColumn::make('status')->label('STATUS')->searchable()->badge()->icon('heroicon-s-square-2-stack')->color(fn(string $state): string => match ($state) {
                    'Absent'     => 'danger',
                    'Late' => 'warning',
                    'Cutting' => 'primary',
                }),
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

    public function mount()
    {
        $this->subject = TeacherSubject::where('id', decrypt(request('id')))->first();

    }

    public function absentStudent($id)
    {
        Attendance::create([
            'teacher_subject_id' => $this->subject->id,
            'student_id'         => $id,
            'classroom_id'       => $this->subject->classroom->id,
            'date'               => Carbon::now(),
            'status'             => 'Absent',
            'time_in'            => Carbon::now()->format('H:i:s'),
        ]);
    }

    public function lateStudent($id)
    {
        Attendance::create([
            'teacher_subject_id' => $this->subject->id,
            'student_id'         => $id,
            'classroom_id'       => $this->subject->classroom->id,
            'date'               => Carbon::now(),
            'status'             => 'Late',
            'time_in'            => Carbon::now()->format('H:i:s'),
        ]);
    }

    public function cuttingStudent($id)
    {
        Attendance::create([
            'teacher_subject_id' => $this->subject->id,
            'student_id'         => $id,
            'classroom_id'       => $this->subject->classroom->id,
            'date'               => Carbon::now(),
            'status'             => 'Cutting',
            'time_in'            => Carbon::now()->format('H:i:s'),
        ]);
    }

    public function render()
    {
        $classroom = $this->subject->classroom;
        $studs     = StudentClassroom::where('classroom_id', $classroom->id)->pluck('student_id')->toArray();
        return view('livewire.teacher.subject-attendance', [
            'students' => Student::whereIn('id', $studs)->when($this->search, function ($record) {
                return $record->where('firstname', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%');
            })->get(),
        ]);
    }
}
