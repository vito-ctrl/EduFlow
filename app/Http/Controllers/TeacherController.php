<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Payment;


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

    public function stats()
{
    $teacher = auth()->user();

    // Teacher courses
    $courses = Course::where('teacher_id', $teacher->id)->pluck('id');

    // Total courses
    $totalCourses = $courses->count();

    // Total enrollments (paid only)
    $totalEnrollments = Enrollment::whereIn('course_id', $courses)
        ->where('status', 'paid')
        ->count();

    // Total students (unique)
    $totalStudents = Enrollment::whereIn('course_id', $courses)
        ->where('status', 'paid')
        ->distinct('user_id')
        ->count('user_id');

    // Total revenue
    $totalRevenue = Payment::whereHas('enrollment', function ($q) use ($courses) {
        $q->whereIn('course_id', $courses)
          ->where('status', 'paid');
    })
    ->where('status', 'success')
    ->sum('amount');

    return response()->json([
        "total_courses" => $totalCourses,
        "total_enrollments" => $totalEnrollments,
        "total_students" => $totalStudents,
        "total_revenue" => $totalRevenue
    ]);
}

}
