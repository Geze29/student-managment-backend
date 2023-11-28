<?php

namespace App\Http\Controllers;

use App\Models\Password_reset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
{
    //
    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $data = [
                "message" => "User Not Exist"
            ];
            return response()->json($data, 401);
        }
        
        $resetCode = rand(1000, 9999);
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'reset_code' => $resetCode,
            'created_at' => now(),
            'updated_at' => now() 
        ]);
        $emailContent = "Hello there!\n\n"
            . "Thank you for using our service. To complete your account verification, please use the following code:\n\n"
            . "Verification Code: $resetCode\n\n"
            . "This code will expire in 15 minutes for security reasons. If you didn't request this verification, please ignore this email.\n\n"
            . "Best regards,\n"
            . "Active Advertising";
        Mail::raw($emailContent, function ($message) use ($request) {
            $message->to($request->input('email'));
            $message->subject('Your Account Verification Code');
        });

        return response()->json(['message' => 'code successfully sent'], 200);
    }

    public function checkCode(Request $request)
    {
        $validateInput = $request->validate([
            'email' => 'required',
            'reset_code' => 'required'
        ]);
        $resetCodes = Password_reset::orderBy('created_at', 'desc')->get()->where('email', $validateInput['email'])->first();
        if ($resetCodes['reset_code'] != $validateInput['reset_code']) {
            return response()->json(["message" => "invalid verification"], 401);
        }
        return response()->json(["message" => "successfully Verified"], 200);
    }
}
