<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TeacherExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
  public function view(): View
    {
        return view('exports.teacher', [
            'teachers' => Teacher::all()
        ]);
    }
}
