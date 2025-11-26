<?php
namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class TeacherImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (! isset($row[1]) || strtolower($row[1]) === 'lastname') {
                continue;
            }

           
            if (empty($row[5])) {
                continue; 
            }

         
            $user = User::firstOrCreate(
                ['email' => $row[5]],
                [
                    'name'     => trim($row[2] . ' ' . $row[1]),
                    'password' => Hash::make($row[0]), 
                    'role'     => 'teacher',
                ]
            );

            $teacherExists = Teacher::where('user_id', $user->id)->exists();
            if (! $teacherExists) {
                Teacher::create([
                    'lastname'   => trim($row[1]),
                    'firstname'  => trim($row[2]),
                    'middlename' => trim($row[3] ?? ''),
                    'birthdate'  => ! empty($row[3]) ? Carbon::parse($row[4]) : null,
                    'user_id'    => $user->id,
                    'teacher_identification_number' => isset($row[0]) ? trim($row[0]) : null, 
                ]);
            }
        }
    }
}
