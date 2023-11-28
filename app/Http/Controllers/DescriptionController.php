<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Description;
use Illuminate\Http\Request;

class DescriptionController extends Controller
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
            return response()->json(Description::all());
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
        //
        try {
            $description = Description::create($request->all());
            return response()->json($description);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Description  $description
     * @return \Illuminate\Http\Response
     */
    public function show(Description $description)
    {
        //
        try {
            return response()->json($description);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Description  $description
     * @return \Illuminate\Http\Response
     */
    public function edit(Description $description)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Description  $description
     * @return \Illuminate\Http\Response
     */
    public function update(CourseRequest $request, Description $description)
    {
        //
        try {
            $description->update($request->all());
            return response()->json($description);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Description  $description
     * @return \Illuminate\Http\Response
     */
    public function destroy(Description $description)
    {
        //
        try {
            $description->delete();
            return response()->json(['result'=>'successfully deleted']);
        } catch (\Throwable $th) {
            return response()->json(['result'=>'fail']);
        }
    }
}
