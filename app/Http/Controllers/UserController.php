<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
class UserController extends Controller
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
            return new UserCollection(User::all());
        } catch (\Throwable $th) {
            return ["result"=>$th];
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
    public function store(UserRequest $request)
    {
        try {
            $validate = $request->validated();
            $user = User::create($validate);
            return ['result'=>'success'];
        } catch (\Throwable $th) {
            return ['result'=>$th];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        return new UserResourse($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        // $user->fname = $request->fname;
        // $user->mname = $request->mname;
        // $user->lname = $request->lname;
        // $user->birthDate = $request->birthDate;
        // $user->phoneNumber = $request->phoneNumber;
        // $user->address = $request->address;
        // $user->imagePath = $request->imagePath;
        // $save = $user->save();
        try {
            $updated = $user->update($request->all());
            return new UserResourse($user);
        } catch (\Throwable $th) {
            return ["result"=>$th];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        $user->delete();
        return ["result"=>"deleted successfuly"];
    }

    

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            $userInfo = '';
            switch ($user->role) {
                case 'student':
                    $userInfo =  User::with('student')->find($user->id);
                    break;
                case 'admid':
                    $userInfo = User::with('admin')->find($user->id);
                    break;
                case 'instructor':
                    $userInfo = User::with('instructor')->find($user->id);
                    break;
                default:                    
                    $userInfo = $user;
                    break;
            }
            return response()->json(['token' => $token,'user'=>$userInfo], 200);
        }
        return response()->json(['user'=>$credentials,'message' => 'Invalid credentials'], 401);

    }

    public function logout(Request $request)
    {

        $user = $request->user();
        
        $user->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()],400);
        }
        
        $user = User::where('email',$request->email)->first();
        
        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }
    

}
