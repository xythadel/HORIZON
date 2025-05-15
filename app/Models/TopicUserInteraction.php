<?php

// app/Models/TopicUserInteraction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicUserInteraction extends Model
{
    protected $fillable = ['user_id', 'topic_id', 'completed'];
}

