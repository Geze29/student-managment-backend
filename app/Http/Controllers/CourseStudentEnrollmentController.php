<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class CourseStudentEnrollmentController extends Controller
{
    //
    public function courseTakenStudents($id)
    {
        try {
            if (!Course::find($id)) {
                return ['result'=>'This course does not exit'];
            } else {
                $students = Course::with('enrollment.student.user')->find($id);
                return $students->enrollment;
            }
            
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function studentTakeCourses($id)
    {
        $student = Student::find($id);
        if($student){
            $enrollents = Enrollment::where('student_id',$id)->with('course')->get();
            return response()->json($enrollents,200);
        }else{
            return response()->json(['message'=>'student doest exist'],400);
        }
    }

    public function instructorCourses($id)
    {
        try {
            return Instructor::find($id)->course;
        } catch (\Throwable $th) {
            return $th;
        }
    }

}