<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class StudentFavorites extends Controller
{
    public function index () {
        // return "hi";
        $user = auth()->user();

        $courses = $user->favorites()->get();
        
        return response()->json([
            "favorits" => $courses 
        ]);
    }

    public function store ($courseId) {
        $user = auth()->user();

        $course = Course::find($courseId);
        
        if(!$course) {
            return response()->json([
                "message" => "Course not founded"
            ], 405);    
        }

        $user->favorites()->syncWithoutDetaching($courseId);

       
        return response()->json([
            "message" => "Course added to favorites"
        ]);
    }
}
