<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentInformation extends Model
{
    protected $guarded = [];

    public function gradeLevel(){
        return $this->belongsTo(GradeLevel::class);
    }

    public function getFullNameAttribute(){
        return "{$this->lastname}, {$this->firstname}";
    }

    public function student(){
        return $this->hasOne(Student::class);
    }
}
