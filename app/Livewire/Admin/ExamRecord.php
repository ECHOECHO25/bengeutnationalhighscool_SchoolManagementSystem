<?php

namespace App\Livewire\Admin;

use App\Models\Exam;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class ExamRecord extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Exam::query())->headerActions([
                CreateAction::make('exam')->icon('heroicon-s-plus-circle')->label('Create Grading')->iconPosition('after')->form([
                    TextInput::make('name')->required()->label('Name'),
                    Select::make('department')->required()->options([
                        'Junior High School' => 'Junior High School',
                        'Senior High School' => 'Senior High School',
                    
                        
                    ])->label('Department'),
                ])->modalWidth('xl'),
            ])
            ->columns([
                TextColumn::make('name')->label('NAME')->searchable(),
                TextColumn::make('department')->label('DEPARTMENT')->searchable(),
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
        return view('livewire.admin.exam-record');
    }
}
