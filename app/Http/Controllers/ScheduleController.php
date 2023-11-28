<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return ['result'=>Schedule::all()];
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
    public function createSchedule(ScheduleRequest $request,Course $course)
    {
        try {
            Schedule::create(array_merge(['course_id'=>$course->id],$request->all()));
            return ["result"=>"success"];
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function showStudent($id)
    {
        try {
            if (!Student::find($id)) {
                return ['result'=>'student does not exist'];
            }
            $enrollments = Student::find($id)->enrollment;
            
            $schedule = [];
            foreach ($enrollments as $enrollment) {
                if ($enrollment->status == "enrolled" || $enrollment->status == "inprogress") {  
                    $course = $enrollment->course;
                    if ($course->status != "completed") {
                        $schedule[] = Course::with('schedule')->find($course->id);
                    }
                }
            }
            return $schedule;
        } catch (\Throwable $th) {
            return $th;
        }
        
    }


    public function showInstructor($id)
    {
        try {
            $course = Instructor::with('course.schedule')->find($id);
            return $course->course;
        } catch (\Throwable $th) {
            return $th;
        }
    }


    public function showCourse($id)
    {
        try {
            return Course::find($id)->schedule;
        } catch (\Throwable $th) {
            return $th;
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
        try {
            $schedule->update($request->all());
            return ['result'=>'success'];
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
        try {
            $schedule->delete();
            return ['result'=>'successfuly deleted'];
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
