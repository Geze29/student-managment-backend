<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstructorRequest;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class InstructorController extends Controller
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
            return response()->json(Instructor::with('user')->get(),200);
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
                'salary'=>'required',
                'experience'=>'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $request['password'] = Hash::make($request->password);
            $user = User::create(array_merge($request->all(),['role'=>'instructor']));
            $instructor = Instructor::create(array_merge(['user_id'=>$user->id],$request->all()));
            return response()->json(['message'=>'success'],201);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function show(Instructor $instructor)
    {
        //
        try {
            return response()->json(Instructor::with('user')->find($instructor->id),200);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
   
    public function update(Request $request, Instructor $instructor)
    {
        //
        try {
            $validator = Validator::make($request->all(),[
                'fname'=>'required',
                'mname' => 'required',
                'lname' => 'required',
                'birthDate' => 'required',
                'phoneNumber' => 'required',
                'address' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $instructor->user->update($request->all());
            $instructor->update($request->all());
            return response()->json(['message'=>'successfully updated'],200);
        } catch (\Throwable $th) {
            return response()->json(['result'=>$th],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instructor $instructor)
    {
        //
        try {
            $instructor->user()->delete();
            $instructor->delete();
            return response()->json(['result'=>'success']);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
