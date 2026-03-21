<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\course;

class Interest extends Model
{
    protected $hidden = ['pivot'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_interests');
    }

    public function course() {
        return $this->belongsToMany(course::class, 'course_interest');
    }

}
