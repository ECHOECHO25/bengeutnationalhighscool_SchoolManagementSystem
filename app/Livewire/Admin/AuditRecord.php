<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\SchoolYear;
use Livewire\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AuditRecord extends Component implements HasForms, HasTable
{
     use InteractsWithTable;
    use InteractsWithForms;

    public function render()
    {
        return view('livewire.admin.audit-record');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(AuditLog::query())->searchable()
            ->columns([
                TextColumn::make('user.name')->label('NAME'),
                TextColumn::make('user.role')->label('ROLE')->formatStateUsing(
                    fn (string $state): string => ucfirst($state)
                ),
                 TextColumn::make('date')->label('DATE')->date(),
                 TextColumn::make('time')->label('TIME')->time('h:i:s A'),
               
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

    
}
