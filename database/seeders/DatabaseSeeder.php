<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Teacher',
            'email' => 'teacher@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        User::create([
            'name' => 'Student',
            'email' => 'student@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        User::create([
            'name' => 'Encoder',
            'email' => 'encoder@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'encoder',
        ]);
        User::create([
            'name' => 'Guidance',
            'email' => 'guidance@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'guidance',
        ]);


            $gradeLevels = [
                ['name' => 'Grade 7',
                'department' => 'Junior High School',
            ],
                ['name' => 'Grade 8',
                'department' => 'Junior High School',
            ],
                ['name' => 'Grade 9',
                'department' => 'Junior High School',
            ],
                ['name' => 'Grade 10',
                'department' => 'Junior High School',
            ],
                ['name' => 'Grade 11',
                'department' => 'Senior High School',
            ],
                ['name' => 'Grade 12',
                'department' => 'Senior High School',
            ],
            ];

            foreach($gradeLevels as $level){
                \App\Models\GradeLevel::create($level);
            };

    }



}
