<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = [];

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }
}
