<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Make sure this is here

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    //  In Course.php (WRONG — this assumes course has a course_id)
    

}
