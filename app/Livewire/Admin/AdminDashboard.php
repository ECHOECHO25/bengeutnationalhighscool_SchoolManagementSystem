<?php

namespace App\Livewire\Admin;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentInformation;
use App\Models\SchoolYear;   // ✅ Add this
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        /** ============================
         *  GET ACTIVE SCHOOL YEAR
         ============================ */
        $activeSchoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$activeSchoolYear) {
            // Prevent errors if no active SY exists
            return view('livewire.admin.admin-dashboard', [
                'gradeLevelCounts' => collect([]),
                'studentCount' => 0,
                'teacherCount' => Teacher::count(),
                'maleStudents' => 0,
                'femaleStudents' => 0,
            ]);
        }

        $schoolYearId = $activeSchoolYear->id;


        /** ============================
         *  STUDENTS PER GRADE LEVEL
         ============================ */
        $gradeLevelCounts = Student::where('school_year_id', $schoolYearId)
            ->selectRaw('grade_level_id, COUNT(*) as total')
            ->groupBy('grade_level_id')
            ->with('gradeLevel')
            ->get()
            ->map(function ($item) {
                return [
                    'name'  => $item->gradeLevel->name ?? 'Grade ' . $item->grade_level_id,
                    'value' => $item->total,
                ];
            });


        /** ============================
         *  TOTAL STUDENTS
         ============================ */
        $students = Student::where('school_year_id', $schoolYearId)->get();

        $studentCount = $students->count();


        /** ============================
         *  GENDER COUNTS
         ============================ */
        $enrolledLRNs = $students->pluck('lrn')->toArray();

        $maleStudents = StudentInformation::whereIn('lrn', $enrolledLRNs)
            ->where('sex', 'Male')
            ->count();

        $femaleStudents = StudentInformation::whereIn('lrn', $enrolledLRNs)
            ->where('sex', 'Female')
            ->count();


        /** ============================
         * TEACHER COUNT
         ============================ */
        $teacherCount = Teacher::count();


        /** ============================
         *  VIEW DATA
         ============================ */
        return view('livewire.admin.admin-dashboard', [
            'gradeLevelCounts' => $gradeLevelCounts,
            'studentCount'     => $studentCount,
            'teacherCount'     => $teacherCount,
            'maleStudents'     => $maleStudents,
            'femaleStudents'   => $femaleStudents,
        ]);
    }
}
