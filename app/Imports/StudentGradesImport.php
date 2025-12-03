<?php

namespace App\Imports;

use App\Models\StudentGrade;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentGradesImport implements ToModel, WithHeadingRow
{
    protected $subjectGradeId;

    public function __construct($subjectGradeId)
    {
        $this->subjectGradeId = $subjectGradeId;
    }

    public function model(array $row)
    {
        // Assume Excel has columns: lrn, grade
        $student = Student::where('lrn', $row['lrn'])->first();
        if (!$student || !isset($row['grade'])) {
            return null;
        }

        // Update or create grade
        return StudentGrade::updateOrCreate(
            [
                'student_id' => $student->id,
                'subject_grade_id' => $this->subjectGradeId,
            ],
            [
                'grade' => $row['grade'],
            ]
        );
    }
}
