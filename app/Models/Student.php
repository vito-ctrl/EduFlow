<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class student extends Model
{
    public function Courses(){
        return $this->HasMany(Course::class);
    }
}
