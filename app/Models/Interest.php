<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Interest extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_interests');
    }

}
