<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class ClassroomImport implements ToCollection
{
   public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $gradeLevels = GradeLevel::where('name', 'like', '%' . $row[1] . '%')->first();

            Classroom::create([
                'building_number' => $row[0],
                'grade_level_id' => $gradeLevels ? $gradeLevels->id : null,
                'section' => $row[2],
                'capacity' => $row[3],
                'school_year_id' => SchoolYear::where('is_active', true)->first()->id ?? '',
            ]);
        }
    }
}
