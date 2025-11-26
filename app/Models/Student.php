<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    public function gradeLevel(){
        return $this->belongsTo(GradeLevel::class);
    }

    public function studentClassrooms(){
        return $this->hasMany(StudentClassroom::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public function subjectGrades(){
        return $this->hasMany(SubjectGrade::class);
    }

    public function getFullNameAttribute(){
        return $this->lastname . ', ' . $this->firstname . ' ' . $this->middlename;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function studentInformation(){
        return $this->belongsTo(StudentInformation::class);
    }
}
