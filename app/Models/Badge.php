<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['title', 'description', 'image'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('claimed_at')->withTimestamps();
    }
}
