<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];
    
    /**
     * Get the topics for the course.
     * 
     * This relationship assumes that topics table has a 'course_id' foreign key.
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}