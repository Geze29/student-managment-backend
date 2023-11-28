<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
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
            return Enrollment::with('course.instructor.user')->with('student.user')->get();
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
    public function store(Request $request)
    {

        $enrollments = Enrollment::all();
        foreach ($enrollments as $enrollment) {
            if ($request->student_id == $enrollment->student_id && $request->course_id == $enrollment->course_id) {
                return response()->json(['message' => 'exists'], 402);
            }
        }

        $course = Course::find($request->course_id);
        $currentDateTime = Carbon::now();

        if ($course->status != 'active') {
            return response()->json(['message' => 'course not active'], 400);
        }

        if ($course->maxCapacity != null) {
            if ($course->enrollmentNumber < $course->maxCapacity) {
                $enrollment = Enrollment::create($request->all());

                $payment = new Payment();
                $payment->amount = $course->fee;
                $payment->enrollment_id = $enrollment->id;
                $payment->paid_at = $currentDateTime->toDateTimeString();
                $payment->save();

                $course->enrollmentNumber++;
                $course->save();
                return response()->json(['result' => 'successfully Enrolled'], 201);
            } else {
                response()->json(['result' => 'course is full']);
            }
        } else {
            $enrollment = Enrollment::create($request->all());

            $payment = new Payment();
            $payment->amount = $course->fee;
            $payment->enrollment_id = $enrollment->id;
            $payment->paid_at = $currentDateTime->toDateTimeString();
            $payment->save();

            $course->enrollmentNumber++;
            $course->save();
            return response()->json(['result' => 'successfully Enrolled'], 201);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function show(Enrollment $enrollment)
    {
        //
        try {
            return Enrollment::with('course.instructor.user')->with('student.user')->find($enrollment->id);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function edit(Enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $enrollment = Enrollment::find($id);
            $enrollment->update($request->all());
            return response()->json(['message' => 'successfully updated'], 201);
        } catch (\Throwable $th) {

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Enrollment  $enrollment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Enrollment $enrollment)
    {
        //
        try {
            $enrollment->status = "dropped";
            $enrollment->course->enrollmentNumber--;
            $enrollment->isPassing = false;
            $enrollment->save();
            $enrollment->course->save();
            return ['result' => 'course dropped'];
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function checkEnrollment(Request $request)
    {
        $validator = Validator::make($request->only(['student_id', 'course_id']), [
            'student_id' => 'required',
            'course_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $enrollments = Enrollment::all();

        foreach ($enrollments as $enrollment) {
            if ($request->student_id == $enrollment->student_id && $request->course_id == $enrollment->course_id) {
                return response()->json(['message' => 'already enrolled'], 402);
            }
        }

        return response()->json(['message' => 'success'], 200);
    }

}