<?php
namespace App\Livewire\Admin;

use App\Models\Student;
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
        return view('livewire.admin.admin-dashboard', [
            'gradeLevelCounts' => $gradeLevelCounts,
            'studentCount' => Student::count(),
            'teacherCount' => \App\Models\Teacher::count(),
        ]);
    }
}
