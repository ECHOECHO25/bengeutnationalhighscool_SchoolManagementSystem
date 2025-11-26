<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    protected $guarded = [];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function subjectGrades(){
        return $this->hasMany(SubjectGrade::class);
    }
}
