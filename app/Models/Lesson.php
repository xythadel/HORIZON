<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'difficulty',
        'content',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}