<?php
namespace App\Livewire\Admin;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentInformation;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        $gradeLevelCounts = Student::selectRaw('grade_level_id, COUNT(*) as total')
            ->groupBy('grade_level_id')
            ->with('gradeLevel')
            ->get()
            ->map(function ($item) {
                return [
                    'name'  => $item->gradeLevel->name ?? 'Grade ' . $item->grade_level_id,
                    'value' => $item->total,
                ];
            });

        // Get gender counts from StudentInformation table (since that's where sex is stored)
        $enrolledLRNs = Student::pluck('lrn')->toArray();

        $maleStudents = StudentInformation::whereIn('lrn', $enrolledLRNs)
            ->where('sex', 'Male')
            ->count();

        $femaleStudents = StudentInformation::whereIn('lrn', $enrolledLRNs)
            ->where('sex', 'Female')
            ->count();

        // Teacher gender counts (if sex column exists in teachers table)
        try {
            $maleTeachers = Teacher::where('sex', 'Male')->count();
            $femaleTeachers = Teacher::where('sex', 'Female')->count();
        } catch (\Exception $e) {
            // If sex column doesn't exist in teachers table
            $maleTeachers = 0;
            $femaleTeachers = 0;
        }

        return view('livewire.admin.admin-dashboard', [
            'gradeLevelCounts' => $gradeLevelCounts,
            'studentCount' => Student::count(),
            'teacherCount' => Teacher::count(),
            'maleStudents' => $maleStudents,
            'femaleStudents' => $femaleStudents,

        ]);
    }
}
