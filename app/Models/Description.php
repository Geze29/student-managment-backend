<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id','requirement','description','contents','material'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
