<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_published',
        'topic_id',
        'questionCategory',
        'quizStatus',
        'answer',
        'questionType',
        'score'

    ];

    // Relationships

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
