<?php
namespace App\Exports;

use App\Models\Student;
use App\Models\StudentClassroom;
use App\Models\StudentInformation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassroomStudentsExport implements FromCollection, WithHeadings
{
    protected $studentIds;

    public function __construct($studentIds)
    {
        $this->studentIds = $studentIds;
    }

    public function collection()
    {
        return Student::whereIn('id', $this->studentIds)
            ->with('gradeLevel')
            ->get()
            ->map(function($student) {
                $info = StudentInformation::find($student->student_information_id);
                return [
                    'LRN' => $student->lrn,
                    'Full Name' => $student->lastname . ', ' . $student->firstname,
                    'Grade Level' => $student->gradeLevel->name ?? 'N/A',
                    'Section' => optional($student->StudentClassrooms->first()->classroom)->section,
                    'Building' => optional($student->StudentClassrooms->first()->classroom)->building_number,
                    'Sex' => $info->sex ?? 'N/A',
                ];
            });
    }

    public function headings(): array
    {
        return ['LRN', 'Full Name', 'Grade Level', 'Section', 'Building', 'Sex'];
    }
}
