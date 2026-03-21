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
        'price',
        'available',
        'teacher_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'course_interest', 'course_id', 'interest_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

}
