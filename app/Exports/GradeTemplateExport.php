<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\StudentClassroom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GradeTemplateExport implements FromCollection, WithHeadings
{
    protected $classroomId;

    public function __construct($classroomId)
    {
        $this->classroomId = $classroomId;
    }

    public function headings(): array
    {
        return [
            'LRN',
            'Student Name',
            'Grade',
        ];
    }

    public function collection()
    {
        $studentIds = StudentClassroom::where('classroom_id', $this->classroomId)
            ->pluck('student_id');

        return Student::whereIn('id', $studentIds)->get()
            ->map(function ($student) {
                return [
                    $student->lrn,
                    $student->fullname ?? ($student->lastname . ', ' . $student->firstname),
                    '' // Empty column for teacher to fill
                ];
            });
    }
}
