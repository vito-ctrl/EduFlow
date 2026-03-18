<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\student;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'nich'
    ];

    public function teachers(){
        return $this->belongsTo(Teacher::class);
    }

    public function students(){
        return $this->HasMany(Student::class);
    }
}
