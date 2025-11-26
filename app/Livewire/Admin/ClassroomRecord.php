<?php
namespace App\Livewire\Admin;

use App\Imports\ClassroomImport;
use App\Models\Classroom;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ClassroomRecord extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use WithFileUploads;

    public $file;

    public function table(Table $table): Table
    {
        return $table
            ->query(Classroom::query())->headerActions([
            CreateAction::make('new')->icon('heroicon-s-plus-circle')->iconPosition('after')->form([
                Grid::make(2)->schema([
                    TextInput::make('building_number')->required()->columnSpan(2),
                    Select::make('grade_level_id')
                        ->label('Grade Level')
                        ->options(GradeLevel::all()->pluck('name', 'id'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state) {
                            $gradeLevel = GradeLevel::where('id', $state)->first();

                            $set('school_level', $gradeLevel && $gradeLevel->department
                                    ? $gradeLevel->department// show department name
                                    : ''
                            );
                        }),
                    TextInput::make('section')->required(),
                    TextInput::make('school_level')->disabled()->columnSpan(2),
                    Select::make('teacher_id')
                        ->label('Adviser')
                        ->options(function () {
                            // Get IDs of teachers already assigned to a classroom
                            $assignedTeacherIds = Classroom::pluck('teacher_id')->filter()->toArray();

                            // Get teachers not yet assigned
                            return Teacher::whereNotIn('id', $assignedTeacherIds)
                                ->get()
                                ->mapWithKeys(function ($teacher) {
                                    return [$teacher->id => "{$teacher->lastname}, {$teacher->firstname}"];
                                });
                        })
                        ->required(),

                ]),
            ])->modalWidth('xl')->modalHeading('Create New Classroom')->slideOver()->action(
                function ($data) {
                    $active = SchoolYear::where('is_active', 1)->first();
                    Classroom::create([
                        'building_number' => $data['building_number'],
                        'grade_level_id'  => $data['grade_level_id'],
                        'section'         => $data['section'],
                        'school_year_id'  => $active ? $active->id : null,
                        'capacity'        => $data['capacity'] ?? null,
                        'teacher_id'      => $data['teacher_id'],
                        'is_active'       => 1,
                    ]);
                }
            ),
            Action::make('export')->label('Export Excel')->color('success')->icon('heroicon-s-arrow-down-on-square')->iconPosition('after')->action(
                function () {
                    return Excel::download(new \App\Exports\ClassroomExport, 'classrooms.xlsx');
                }
            ),
            Action::make('import')->label('Import Excel')->color('info')->icon('heroicon-s-arrow-up-on-square')->iconPosition('after')->action(
                function () {
                    $this->dispatch('open-modal', id: 'import-classroom');
                }
            ),
        ])
            ->columns([
                TextColumn::make('building_number')->label('BUILDING NO.')->searchable(),
                TextColumn::make('gradeLevel.name')->label('GRADE LEVEL')->searchable(),
                TextColumn::make('gradeLevel.department')->label('SCHOOL LEVEL')->searchable(),
                TextColumn::make('section')->label('SECTION')->searchable(),
                TextColumn::make('teacher.id')->label('ADVISER')->searchable()->formatStateUsing(
                    fn($record) => $record->teacher ? $record->teacher->lastname . ', ' . $record->teacher->firstname : ''
                ),

            ])
            ->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([
                    DeleteAction::make('delete'),
                    Action::make('assign')->label('Assign Teacher')->icon('heroicon-s-plus')->form([
                        Select::make('teacher_id')->label('Teacher')->options(
                            Teacher::all()->mapWithKeys(function ($teacher) {
                                return [$teacher->id => $teacher->lastname . ', ' . $teacher->firstname];
                            }),
                        ),
                    ])->modalWidth('lg')->modalSubheading('Assign Teacher to Classroom')->action(
                        function ($record, $data) {
                            $record->update([
                                'teacher_id' => $data['teacher_id'],
                            ]);
                        }
                    )->hidden(fn($record) => $record->teacher_id !== null),
                ]),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function importClassroom()
    {
        Excel::import(new ClassroomImport(), $this->file);
        $this->dispatch('close-modal', id: 'import-classroom');
    }

    public function render()
    {
        return view('livewire.admin.classroom-record');
    }
}
