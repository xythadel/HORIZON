<?php

// app/Models/QuizUserInteraction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizUserInteraction extends Model
{
    protected $fillable = ['user_id', 'quiz_id', 'completed'];
}

