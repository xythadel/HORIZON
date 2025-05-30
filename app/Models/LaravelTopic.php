<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaravelTopic extends Model
{
     protected $fillable = [
        'course_id', 
        'title', 
        'content',
        'module_name', // Assuming you want to track the module name
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
