<?php
namespace App\Livewire\Admin;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use function Flasher\SweetAlert\Prime\sweetalert;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ResetUserPasswords extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->whereIn('role', ['student', 'teacher']))
            ->columns([
                TextColumn::make('name')->label('NAME')->searchable(),
                TextColumn::make('email')->label('EMAIL')->searchable(),
                TextColumn::make('role')
                    ->label('ROLE')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'student' => 'success',
                        'teacher' => 'info',
                        default => 'gray',
                    }),
               
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('User Type')
                    ->options([
                        'student' => 'Students',
                        'teacher' => 'Teachers',
                    ]),
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Action::make('reset_password')
                    ->label('Reset Password')
                    ->button()
                    ->color('warning')
                    ->icon('heroicon-s-key')
                    ->iconPosition('after')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Password')
                    ->modalDescription(fn(User $record): string =>
                        "Are you sure you want to reset the password for {$record->name}? " .
                        "The password will be set to their " .
                        ($record->role === 'student' ? 'LRN' : 'Teacher ID') . "."
                    )
                    ->action(function (User $record) {
                        $this->resetPassword($record->id);
                    }),
            ])
            ->bulkActions([
                BulkAction::make('reset_passwords')
                    ->label('Reset Passwords')
                    ->color('danger')
                    ->icon('heroicon-s-key')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Multiple Passwords')
                    ->modalDescription('Are you sure you want to reset passwords for all selected users? Passwords will be set to their respective LRN/Teacher ID.')
                    ->action(function (Collection $records) {
                        foreach ($records as $user) {
                            $this->resetPassword($user->id);
                        }
                        sweetalert()->success('Passwords reset successfully for ' . $records->count() . ' users!');
                    }),
            ]);
    }

    public function resetPassword($userId)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($userId);

            // Determine the password based on role
            $newPassword = null;

            if ($user->role === 'student') {
                $student = Student::where('user_id', $user->id)->first();
                if ($student) {
                    $newPassword = $student->lrn;
                }
            } elseif ($user->role === 'teacher') {
                $teacher = Teacher::where('user_id', $user->id)->first();
                if ($teacher) {
                    $newPassword = $teacher->teacher_identification_number;
                }
            }

            if (!$newPassword) {
                sweetalert()->error('Could not find ID/LRN for this user!');
                DB::rollBack();
                return;
            }

            // Update password
            $user->password = Hash::make($newPassword);
            $user->save();

            DB::commit();
            sweetalert()->success("Password for {$user->name} has been reset to their " .
                ($user->role === 'student' ? 'LRN' : 'Teacher ID') . "!");

        } catch (\Exception $e) {
            DB::rollBack();
            sweetalert()->error('Error resetting password: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.reset-user-passwords');
    }
}
