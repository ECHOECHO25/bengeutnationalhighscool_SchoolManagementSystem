<?php

namespace App\Exports;

use App\Models\Classroom;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClassroomExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
   public function view(): View
    {
        return view('exports.classroom', [
            'classrooms' => Classroom::all()
        ]);
    }
}
