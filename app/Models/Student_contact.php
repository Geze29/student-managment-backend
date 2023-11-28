<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id','contactEmail','contactFname','contactLname',
        'contactPhone','relation'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
