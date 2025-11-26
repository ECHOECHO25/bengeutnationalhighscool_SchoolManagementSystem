<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $guarded =[];

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function studentClassrooms(){
        return $this->hasMany(StudentClassroom::class);
    }

    public function teacherSubjects(){
        return $this->hasMany(TeacherSubject::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public function schoolYear(){
        return $this->belongsTo(SchoolYear::class);
    }
}
