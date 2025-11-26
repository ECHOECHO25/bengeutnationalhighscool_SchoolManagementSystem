<?php
namespace App\Livewire\Admin;

use App\Models\SchoolYear;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SchoolYearRecord extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(SchoolYear::query())->searchable()->headerActions([
            CreateAction::make('new')->label('New School Year')->icon('heroicon-m-plus-circle')->iconPosition('after')->form([
                DatePicker::make('start_date')->required()->label('Start Date'),
                DatePicker::make('end_date')->required()->label('End Date'),
            ])->modalWidth('xl'),
        ])
            ->columns([
                TextColumn::make('start_date')->label('START DATE')->date(),
                TextColumn::make('end_date')->label('END DATE')->date(),
                ToggleColumn::make('is_active')->label(' ACTIVE')->onColor('success')->offColor('danger')->offIcon('heroicon-s-x-mark')->onIcon('heroicon-s-check')
                    ->afterStateUpdated(function ($state, $record) {
                        if ($state) {
                            // When a record is activated, deactivate all others
                            $record->newQuery()
                                ->where('id', '!=', $record->id)
                                ->update(['is_active' => false]);

                            return redirect(route('admin.school-year'));
                        }
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

    public function render()
    {
        return view('livewire.admin.school-year-record');
    }
}
