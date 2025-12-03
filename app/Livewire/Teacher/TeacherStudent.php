<?php
namespace App\Livewire\Teacher;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentInformation;
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
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClassroomStudentsExport;

class TeacherStudent extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $students;
    public $maleCount = 0;
    public $femaleCount = 0;
    public $excelView = false; // toggle between table/excel style

    // Filament table configuration
    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Student::query()
                    ->when(!empty($this->students), function ($q) {
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
            ->filters([])
            ->actions([
                EditAction::make('edit')->color('success')->badge(),
                Action::make('view')->label('View Record')->badge()->color('warning')->icon('heroicon-s-eye'),
            ])
            ->bulkActions([])
            ->contentGrid([
                'md' => 4,
                'xl' => 5,
            ]);
    }

    // Load male/female counts
    public function loadGenderCounts()
    {
        if (empty($this->students)) {
            $this->maleCount = 0;
            $this->femaleCount = 0;
            return;
        }

        $this->maleCount = StudentInformation::whereHas('student', function($query) {
            $query->whereIn('id', $this->students);
        })->where('sex', 'Male')->count();

        $this->femaleCount = StudentInformation::whereHas('student', function($query) {
            $query->whereIn('id', $this->students);
        })->where('sex', 'Female')->count();
    }

    // Toggle Excel/Table view
    public function toggleExcelView()
    {
        $this->excelView = !$this->excelView;
    }

    // Export to Excel
    public function exportExcel()
    {
        if (empty($this->students)) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'No students to export',
                'icon' => 'warning'
            ]);
            return;
        }

        return Excel::download(new ClassroomStudentsExport($this->students), 'classroom_students.xlsx');
    }

    public function render()
    {
        $classroom = Classroom::where('teacher_id', auth()->user()->teacher->id)->first();
        $this->students = StudentClassroom::where('classroom_id', $classroom->id)
            ->pluck('student_id')
            ->toArray();

        $this->loadGenderCounts();

        return view('livewire.teacher.teacher-student', [
            'classroom' => $classroom,
            'excelView' => $this->excelView,
            'studentsData' => Student::whereIn('id', $this->students)->get(),
        ]);
    }
}
