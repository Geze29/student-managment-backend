<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'courseCode','courseName','instructor_id','maxCapacity',
        'enrollmentNumber','fee','status',
        'classStartDate','classEndDate','erollmentType','dayTaken',
        'backgroundURL'
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function description()
    {
        return $this->hasMany(Description::class);
    }
    
    public function enrollment()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function certificate()
    {
        return $this->hasMany(Certificate::class);
    }
}
