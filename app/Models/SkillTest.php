<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'language',
        'test_input',
        'expected_output',
        'topic_id',
        'score',
        'codesnippet'
    ];
    public function attempts()
    {
        return $this->hasMany(SkillAttempts::class);
    }
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
