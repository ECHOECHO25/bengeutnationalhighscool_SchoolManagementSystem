<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    protected $guarded = [];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }


    public function studentInformation(){
        return $this->hasOne(StudentInformation::class);
    }
}
