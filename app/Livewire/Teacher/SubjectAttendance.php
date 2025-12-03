<?php
namespace App\Livewire\Teacher;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\TeacherSubject;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class SubjectAttendance extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $subject;
    public $search;

    // Manual entry form data
    public $manualStudentId;
    public $manualDate;
    public $manualTimeIn;
    public $manualStatus = 'Absent';
    public $showManualModal = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(Attendance::query()->where('teacher_subject_id', $this->subject->id)->orderBy('date', 'desc'))
            ->columns([
                TextColumn::make('date')->label('DATE')->date('M d, Y')->searchable()->sortable(),
                TextColumn::make('time_in')->label('TIME')->time('h:i A')->searchable(),
                TextColumn::make('student')->label('STUDENT')->searchable()->sortable()
                    ->formatStateUsing(fn($record) => $record->student->firstname . ' ' . $record->student->lastname),
                    TextColumn::make('monthly_absents')
    ->label('MONTH ABSENT')
    ->alignCenter()
    ->badge()
    ->color(fn ($state) => match (true) {
        $state >= 4 => 'danger',
        $state >= 3 => 'warning',
        $state >= 1 => 'primary',
        default => 'gray',
    })
    ->getStateUsing(fn ($record) => $this->getMonthlyAbsents($record->student_id)),
                TextColumn::make('status')->label('STATUS')->searchable()->badge()
                    ->icon('heroicon-s-square-2-stack')
                    ->color(fn(string $state): string => match ($state) {
                        'Absent' => 'danger',
                        'Late' => 'warning',
                        'Cutting' => 'primary',
                    }),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->action(function (Attendance $record, array $data) {
                        $record->update([
                            'date' => $data['date'],
                            'time_in' => $data['time_in'],
                            'status' => $data['status'],
                        ]);
                        sweetalert()->success('Attendance updated successfully!');
                    })
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date')
                            ->label('Date')
                            ->required()
                            ->default(fn($record) => $record->date),
                        \Filament\Forms\Components\TimePicker::make('time_in')
                            ->label('Time In')
                            ->required()
                            ->default(fn($record) => $record->time_in),
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'Absent' => 'Absent',
                                'Late' => 'Late',
                                'Cutting' => 'Cutting',
                            ])
                            ->required()
                            ->default(fn($record) => $record->status),
                    ])
                    ->modalWidth('md'),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Attendance $record) {
                        $record->delete();
                        sweetalert()->success('Attendance deleted successfully!');
                    }),
            ])
            ->bulkActions([
                BulkAction::make('delete')
                    ->label('Delete Selected')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        $records->each->delete();
                        sweetalert()->success('Selected attendance records deleted successfully!');
                    }),
            ]);
    }

    public function mount()
    {
        $this->subject = TeacherSubject::where('id', decrypt(request('id')))->first();
        $this->manualDate = Carbon::now()->format('Y-m-d');
        $this->manualTimeIn = Carbon::now()->format('H:i');
    }

    public function getMonthlyAbsents($studentId)
{
    return Attendance::where('teacher_subject_id', $this->subject->id)
        ->where('student_id', $studentId)
        ->where('status', 'Absent')
        ->whereMonth('date', now()->month)
        ->whereYear('date', now()->year)
        ->count();
}


    public function absentStudent($id)
    {
        Attendance::create([
            'teacher_subject_id' => $this->subject->id,
            'student_id' => $id,
            'classroom_id' => $this->subject->classroom->id,
            'date' => Carbon::now(),
            'status' => 'Absent',
            'time_in' => Carbon::now()->format('H:i:s'),
        ]);

        sweetalert()->success('Student marked as absent!');
    }

    public function lateStudent($id)
    {
        Attendance::create([
            'teacher_subject_id' => $this->subject->id,
            'student_id' => $id,
            'classroom_id' => $this->subject->classroom->id,
            'date' => Carbon::now(),
            'status' => 'Late',
            'time_in' => Carbon::now()->format('H:i:s'),
        ]);

        sweetalert()->success('Student marked as late!');
    }

    public function cuttingStudent($id)
    {
        Attendance::create([
            'teacher_subject_id' => $this->subject->id,
            'student_id' => $id,
            'classroom_id' => $this->subject->classroom->id,
            'date' => Carbon::now(),
            'status' => 'Cutting',
            'time_in' => Carbon::now()->format('H:i:s'),
        ]);

        sweetalert()->success('Student marked as cutting!');
    }

    public function openManualModal()
    {
        $this->showManualModal = true;
        $this->manualDate = Carbon::now()->format('Y-m-d');
        $this->manualTimeIn = Carbon::now()->format('H:i');
        $this->manualStatus = 'Absent';
        $this->manualStudentId = null;
        $this->dispatch('open-modal', id: 'manual-attendance');
    }

    public function saveManualAttendance()
    {
        $this->validate([
            'manualStudentId' => 'required|exists:students,id',
            'manualDate' => 'required|date',
            'manualTimeIn' => 'required',
            'manualStatus' => 'required|in:Absent,Late,Cutting',
        ]);

        // Check if attendance already exists
        $exists = Attendance::where('teacher_subject_id', $this->subject->id)
            ->where('student_id', $this->manualStudentId)
            ->whereDate('date', $this->manualDate)
            ->exists();

        if ($exists) {
            sweetalert()->error('Attendance already recorded for this student on this date!');
            return;
        }

        Attendance::create([
            'teacher_subject_id' => $this->subject->id,
            'student_id' => $this->manualStudentId,
            'classroom_id' => $this->subject->classroom->id,
            'date' => $this->manualDate,
            'status' => $this->manualStatus,
            'time_in' => $this->manualTimeIn,
        ]);

        sweetalert()->success('Manual attendance added successfully!');
        $this->showManualModal = false;
        $this->dispatch('close-modal', id: 'manual-attendance');
        $this->reset(['manualStudentId', 'manualDate', 'manualTimeIn', 'manualStatus']);
    }

    public function render()
    {
        $classroom = $this->subject->classroom;
        $studs = StudentClassroom::where('classroom_id', $classroom->id)->pluck('student_id')->toArray();

        return view('livewire.teacher.subject-attendance', [
            'students' => Student::whereIn('id', $studs)->when($this->search, function ($record) {
                return $record->where('firstname', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%');
            })->get(),
            'allStudents' => Student::whereIn('id', $studs)->orderBy('lastname')->get(),
        ]);
    }
}
