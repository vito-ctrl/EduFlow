<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;

class teacher extends Model
{
    public function courses(){
        return $this->hasMany(Course::class);
    }
}
