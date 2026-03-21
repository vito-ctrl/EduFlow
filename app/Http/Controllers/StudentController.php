<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;

class StudentController extends Controller
{
    public function AvailableCourses () {
        $courses = Course::with('interests')
                    ->where('available', 1)
                    ->get()
                    ->map(function ($course) {
                        $course->interests = $course->interests->pluck('name');
                        return $course;
                    });

        return response()->json([
            "available_courses " => $courses
        ], 200); 
    }

    public function MatchCourses () {
        $user = auth()->user();
        $interestIds = $user->interests->pluck('id');

        $courses = Course::with('interests')
                    ->where('available', 1)
                    ->whereHas('interests', function ($query) use ($interestIds) {
                        $query->whereIn('interests.id', $interestIds);
                    })
                    ->get();

        return response()->json([
            "metched_courses" => $courses
        ]);
    }
}
