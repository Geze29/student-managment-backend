<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Student;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function show($id)
    {
        $student = Student::find($id)->user;
        if (!$student) {
            return response()->json(['message'=>'user not exists'],400);
        }

        $certificates = Certificate::where('student_id',$id);
        return response()->json($certificates,200);
    }
}
