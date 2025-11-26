<?php
namespace App\Livewire\Admin;

use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Student;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class StudentRecord extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Student::query()->where('school_year_id', SchoolYear::where('is_active', true)->first()->id))
            ->columns([
                Stack::make([
                    ViewColumn::make('status')->view('filament.tables.avatar'),
                    TextColumn::make('firstname')->formatStateUsing(
                        fn($record) => $record->lastname . ', ' . $record->firstname
                    )->searchable(['lastname', 'firstname']),
                    TextColumn::make('lrn')->searchable(),
                    TextColumn::make('gradeLevel.name')->formatStateUsing(
                        fn($record) => $record->gradeLevel->name. ' - ' . $record->StudentClassrooms->first()->classroom->section
                    )
                ]),

            ])
            ->filters([
                 SelectFilter::make('grade_level_id')
                    ->label('Grade Level')
                    ->options(
                        GradeLevel::all()->pluck('name', 'id')->toArray()
                    ),
            ])->filtersTriggerAction(
            fn(Action $action) => $action
                ->button()
                ->label('Filter'),

        )

            ->bulkActions([
                // ...
            ])->contentGrid([
            'md' => 4,
            'xl' => 5,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.student-record');
    }
}
