<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'course_id',
        'module_name', // Assuming you want to track the module name
         // make sure this exists in your topics table
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function completedTopics()
    {
    return $this->belongsToMany(\App\Models\Topic::class, 'topic_user')->withTimestamps();
    }

    public function quizzes()
    {
    return $this->hasMany(Quiz::class);
    }
}
