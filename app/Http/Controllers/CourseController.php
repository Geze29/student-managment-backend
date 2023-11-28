<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Description;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            $courses = Course::all();
            $data = [];
            foreach ($courses as $course) {
                if ($course->instructor_id) {
                    $data[] =$course->with('instructor.user')->with('description')->find($course->id);
                } else {
                    $data[] = $course->with('description')->find($course->id);
                }
            }
            return response()->json($data,200);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseRequest $request)
    {
        try {
            if (Course::where('courseCode',$request->courseCode)->first()) {
                return response()->json(['message'=>'course code must be unique'],400);
            }else{
                $course = Course::create($request->all());
                Description::create(array_merge(['course_id'=>$course->id],$request->all()));
                return response()->json(['message'=>'creaded successfully'],201);
            }
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
        try {
            if ($course->instructor_id) {
                return Course::with('instructor.user')->with('description')->find($course->id);
            }else {
                return response()->json($course);
            }
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
        try {
            $course->update($request->all());
            $course->description->update($request->all());
            return response()->json(['message'=>'success'],201);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
        try {
            $course->delete();
            $course->description()->delete();
            return response()->json(["result"=>"successfuly deleted"]);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
