<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectGrade extends Model
{
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacherSubject(){
        return $this->belongsTo(TeacherSubject::class);
    }

    public function studentGrades(){
        return $this->hasMany(StudentGrade::class);
    }

    public function exams(){
        return $this->hasMany(Exam::class);
    }
}
