<?php
namespace App\Livewire\Teacher;

use App\Models\Classroom;
use App\Models\SchoolYear;
use App\Models\TeacherSubject;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TeacherSubjectRecord extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(TeacherSubject::query()->where('teacher_id', auth()->user()->teacher->id)->where('school_year_id', SchoolYear::where('is_active', true)->first()->id))
            ->headerActions([
                CreateAction::make('new')->label('New Subjects')->icon('heroicon-m-plus-circle')->iconPosition('after')->form([
                    TextInput::make('name')->required(),
                    TextInput::make('schedule')->required(),
                    Select::make('classroom')->label('Select Classroom')->required()->options(
                        Classroom::all()->mapWithKeys(function ($record) {
                            return [$record->id => $record->building_number . ' - ' . $record->gradeLevel->name . ' ' . $record->section];
                        })
                    ),
                ])->modalWidth('lg')->modalHeading('Create Subject')->action(
                    function ($data) {
                        TeacherSubject::create([
                            'name'           => $data['name'],
                            'schedule'       => $data['schedule'],
                            'classroom_id'   => $data['classroom'],
                            'teacher_id'     => auth()->user()->teacher->id,
                            'school_year_id' => SchoolYear::where('is_active', true)->first()->id,
                        ]);
                    }
                ),
            ])
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
                // ActionGroup::make([
                //     DeleteAction::make('delete'),
                //     Action::make('attendances')->color('success')->icon('heroicon-s-calendar'),
                //     Action::make('grades')->color('warning')->icon('heroicon-o-clipboard'),
                // ])->button()->size('xs')->label('Actions'),
                 Action::make('attendances')->color('success')->button()->badge()->icon('heroicon-s-calendar')->action(
                    function($record){
                        sleep(1);
                        return redirect()->route('teacher.subject.attendance', ['id' => encrypt($record->id)]);
                    }
                 ),
                    Action::make('grades')->color('warning')->button()->badge()->icon('heroicon-o-clipboard')->action(
                        function($record){
                            sleep(1);
                            return redirect()->route('teacher.subject.grade', ['id' => encrypt($record->id)]);
                        }
                    ),
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
        return view('livewire.teacher.teacher-subject-record');
    }
}
