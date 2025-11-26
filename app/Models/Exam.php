<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    
    protected $guarded = [];

    public function subjectGrade(){
        return $this->belongsTo(SubjectGrade::class);
    }
}
