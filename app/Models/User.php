<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory , HasApiTokens , Notifiable;

    protected $fillable = [
        'email','fname','mname','lname','password',
        'birthDate','gender','phoneNumber',
        'educationLevel','address','role',
        'imagePath'
    ];

    function student()
    {
        return $this->hasOne(Student::class);
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }
}