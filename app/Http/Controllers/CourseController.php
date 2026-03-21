<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index(){
        $courses = Course::all();
        return response()->json([
            "courses" => $courses
        ], 201);
    }

    public function store(Request $request){
        
        $request->validate([
            'title' => 'required|string|max:250',
            'description' => 'required|string',
            'price' => 'required',
            'available' => 'required|boolean',
            'interests' => 'required|array',
            'interests.*' => 'exists:interests,id'
        ]);

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'available' => $request->available,
            'teacher_id' => auth()->user()->id,
        ]);

        $course->interestS()->attach($request->interests);

        return response()->json([
            "message" => 'course created succefully',
            "course" => $course
        ], 201);
    }

    public function show($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $course
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $request->validate([
            'title' => 'sometimes|string|max:250',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric'
        ]);

        $course->update($request->only(['title', 'description', 'price']));

        return response()->json([
            'status' => 'success',
            'message' => 'Course updated successfully',
            'data' => $course
        ], 200);
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully'
        ], 200);
    }
    
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
}
