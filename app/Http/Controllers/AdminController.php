<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResourse;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
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
            return response()->json(Admin::with('user')->get(),200);
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
                "imagePath"=>"required",
                'salary'=>'required',
                'experience'=>'required',
                'responsibility'=>'required'
            ]);         

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $request['password']= Hash::make($request->password);
            $user = User::create(array_merge($request->all(),['role'=>'admin']));
            $admin = Admin::create(array_merge(['user_id'=>$user->id],$request->all()));
            
            return response()->json(['message'=>'success'],201);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
        try {
            return response()->json(Admin::with('user')->find($admin->id),200);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
        try {
            if (!$admin) {
                return response()->json(['message'=>'user not exist'],404);
            }

            $admin->user->update($request->all());
            $admin->update($request->all());
            return response()->json(['message'=>'successfully Updated'],202);
        } catch (\Throwable $th) {
            return response()->json(['result'=>$th]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
        try {
            $admin->user()->delete();
            $admin->delete();
            return response()->json(['result'=>'success']);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
