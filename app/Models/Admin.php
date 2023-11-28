<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id','salary','experience','responsibility'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
