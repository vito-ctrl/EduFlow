<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;

class TeacherController extends Controller
{
    public function getMyStudents()
    {
        $teacher = auth()->user();

        $students = Enrollment::whereHas('course', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })
        ->where('status', 'paid')
        ->with(['user', 'course'])
        ->get();

        return response()->json([
            "students" => $students
        ]);
    }
}
