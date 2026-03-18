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
            'teacher_id' => 'required'
        ]);

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'teacher_id' => $request->teacher_id
        ]);

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
    
}
