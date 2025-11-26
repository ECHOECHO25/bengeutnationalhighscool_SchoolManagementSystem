<?php
namespace App\Livewire\Admin;

use App\Imports\TeacherImport;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class TeacherRecord extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $file;

    public function table(Table $table): Table
    {
        return $table
            ->query(Teacher::query())->headerActions([
            CreateAction::make('new')->icon('heroicon-s-plus-circle')->iconPosition('after')->label('New Teacher')->form([
                TextInput::make('teacher_id')->label('Teacher ID')->required(),
                TextInput::make('lastname')->required(),
                TextInput::make('firstname')->required(),
                TextInput::make('middlename'),
                TextInput::make('email')->email()->required(),
                TextInput::make('birthdate')->type('date')->required(),
            ])->modalWidth('lg')->slideOver()->action(
                function ($data) {
                    $user = User::create([
                        'name'     => $data['firstname'] . ' ' . $data['lastname'],
                        'email'    => $data['email'],
                        'password' => bcrypt($data['teacher_id']),
                        'role'     => 'teacher',
                    ]);
                    Teacher::create([
                        'lastname'                      => $data['lastname'],
                        'firstname'                     => $data['firstname'],
                        'middlename'                    => $data['middlename'],
                        'birthdate'                     => $data['birthdate'],
                        'user_id'                       => $user->id,
                        'teacher_identification_number' => $data['teacher_id'],
                    ]);
                }
            ),
            Action::make('export')->color('success')->label('Export Excel')->action(
                function () {
                    return Excel::download(new \App\Exports\TeacherExport, 'teachers.xlsx');
                }
            ),
            Action::make('import')->color('info')->label('Import Excel')->action(
                function () {
                    $this->dispatch('open-modal', id: 'import-teacher');
                }
            ),
        ])
            ->columns([
                TextColumn::make('teacher_identification_number')->label('TEACHER ID')->searchable(),
                TextColumn::make('lastname')->label('LAST NAME')->searchable(),
                TextColumn::make('firstname')->label('FIRST NAME')->searchable(),
                TextColumn::make('middlename')->label('MIDDLE NAME')->searchable(),
                TextColumn::make('user.email')->label('EMAIL')->searchable(),
                TextColumn::make('birthdate')->label('BIRTHDATE')->date()->searchable(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('classroom')->label('Assign Classroom')->color('warning')->icon('heroicon-s-academic-cap')
                        ->hidden(fn($record) => Classroom::where('teacher_id', $record->id)->exists()),
                    EditAction::make('edit')->label('Edit')->color('success')->form([
                        TextInput::make('teacher_identification_number')->label('Teacher ID')->required(),
                        TextInput::make('lastname')->required(),
                        TextInput::make('firstname')->required(),
                        TextInput::make('middlename'),
                        // TextInput::make('email')->email()->required(),
                        TextInput::make('birthdate')->type('date')->required(),
                    ])->modalWidth('xl')->slideOver(),
                ]),
            ])
            ->bulkActions([
                BulkAction::make('delete')->color('danger')->icon('heroicon-s-trash')->size('xs')
                    ->requiresConfirmation()
                    ->action(fn(Collection $records) => $records->each->delete()),
            ]);
    }
    public function importTeacher()
    {
        Excel::import(new TeacherImport(), $this->file);
        $this->dispatch('close-modal', id: 'import-teacher');
    }

    public function render()
    {
        return view('livewire.admin.teacher-record');
    }
}
