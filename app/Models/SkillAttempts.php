<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillAttempts extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_test_id',
        'submitted_code',
        'status',
        'output',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function skillTest()
    {
        return $this->belongsTo(SkillTest::class);
    }
}
