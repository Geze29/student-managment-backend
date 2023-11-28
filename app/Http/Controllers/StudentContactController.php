<?php

namespace App\Http\Controllers;

use App\Models\Student_contact;
use Illuminate\Http\Request;

class StudentContactController extends Controller
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
            return Student_contact::all();
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
            Student_contact::create($request->all());
            return response()->json(['result'=>'success']);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student_contact  $student_contact
     * @return \Illuminate\Http\Response
     */
    public function show(Student_contact $student_contact)
    {
        //
        try {
            return response()->json($student_contact);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student_contact  $student_contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Student_contact $student_contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student_contact  $student_contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $student_contact = Student_contact::find($id);
            $student_contact->update($request->all());
            return response()->json(['message'=>'successfully updated'],202);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student_contact  $student_contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student_contact $student_contact)
    {
        //
        try {
            $student_contact->delete();
            return response()->json(['result'=>'success']);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
