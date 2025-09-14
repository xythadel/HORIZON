<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['title', 'description', 'image' ,'topic_id','course'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('claimed_at')->withTimestamps();
    }
    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}
