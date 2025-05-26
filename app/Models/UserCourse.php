<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
     protected $fillable = ['user_id', 'course_id', 'last_topic_id', 'completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lastTopic()
    {
        return $this->belongsTo(Topic::class, 'last_topic_id');
    }
}
