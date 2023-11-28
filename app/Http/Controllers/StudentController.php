<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Student;
use App\Models\Student_contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return response()->json(Student::with('user')->with('student_contact')->get(),200);
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
        //
        try {

            $validator = Validator::make($request->all(),[
                "email"=>"required|email|unique:users",
                "fname"=>"required",
                "mname"=>"required",
                "lname"=>"required",
                "password"=>"required|min:6",
                "birthDate"=>"required",
                "gender"=>"required",
                "phoneNumber"=>"required",
                "educationLevel"=>"required",
                "address"=>"required",
                "imagePath"=>"sometimes",
                "contactEmail"=>"email",
                "contactFname"=>"",
                "contactLname"=>"",
                "contactPhone"=>"",
                "relation"=>""
            ]);  

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $request['password']= Hash::make($request->password); 
            $user = User::create(array_merge($request->all(),['role'=>'student']));
            $student = Student::create(['user_id'=>$user->id]);
            Student_contact::create(array_merge(['student_id'=>$student->id],$request->all()));
            
            return response()->json(['result'=>'success'],201);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        try {
            if (!$student) {
                return response()->json(['message'=>'student not found'],404);
            }
            return response()->json([$student::with('user')->with('student_contact')->find($student->id)],200);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $student = Student::find($id);
            if (!$student) {
                return response()->json(['message'=>'student does not exist'],400);
            }
            $student->user->update($request->all());
            $student->student_contact->update($request->all());
            return response()->json(['message'=>'success'],200);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
        try {
            $student->user()->delete();
            $student->student_contact()->delete();
            $student->delete();
            return response()->json(['result'=>'success']);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
