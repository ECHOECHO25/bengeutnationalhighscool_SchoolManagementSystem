<?php
namespace App\Livewire\Guidance;

use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentComment;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class GuidanceDashboard extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Student::query())
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
                ActionGroup::make([
                    Action::make('add_comment')->label('Add Comment')->color('primary')->icon('heroicon-s-plus-circle')
                        ->form([
                            // Form fields for adding a comment
                            Textarea::make('comment')
                                ->label('Comment')
                                ->required()
                                ->rows(4),
                        ])->modalWidth('xl')->action(
                            function ($record, $data){
                                sleep(1);
                                StudentComment::create([
                                    'student_id'     => $record->id,
                                    'comment'        => $data['comment'],
                                    'school_year_id' => SchoolYear::where('is_active', true)->first()->id,
                                ]);
                                
                            }
                        ),
                        
                    Action::make('view_comment')->label('View Comments')->color('warning')->icon('heroicon-s-chat-bubble-bottom-center-text')->slideOver()->form([
                        ViewField::make('rating')
                            ->view('filament.forms.comments'),

                    ])->modalWidth('xl')->modalSubmitAction(false),
                ]),
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
        return view('livewire.guidance.guidance-dashboard');
    }
}
