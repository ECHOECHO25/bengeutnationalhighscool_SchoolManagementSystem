<?php

namespace App\Livewire\Admin;

use App\Models\SchoolYear;
use App\Models\Student;
use Carbon\Carbon;
use Livewire\Component;

class Report extends Component
{
    public $selectedReport;
    public $schoolYear;
    public $year_name;

    public function updatedSchoolYear($value)
    {
       $sy = SchoolYear::where('id', $value)->first();


$this->year_name = Carbon::parse($sy->start_date)->format('Y') . ' - ' . Carbon::parse($sy->end_date)->format('Y');
    }
    public function render()
    {
        return view('livewire.admin.report',[
            'school_years' => SchoolYear::all(),
            'students' => Student::when($this->schoolYear, function($query) {
                $query->where('school_year_id', $this->schoolYear);
            })->get(),
        ]);
    }
}
