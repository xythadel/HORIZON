<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserCourse;

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
     public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Calculate the completion percentage for a specific user.
     */
    public function calculateProgressForUser($userId)
    {
        $topicIds = $this->topics()->pluck('id');
        $quizIds = $this->quizzes()->pluck('id');
        
        $totalItems = count($topicIds) + count($quizIds);
        if ($totalItems === 0) return 0;
        
        $completedTopics = Topic::where('user_id', $userId)
            ->whereIn('topic_id', $topicIds)
            ->where('completed', true)
            ->count();
            
        $completedQuizzes = Quiz::where('user_id', $userId)
            ->whereIn('quiz_id', $quizIds)
            ->where('completed', true)
            ->count();
            
        $completedItems = $completedTopics + $completedQuizzes;
        
        return round(($completedItems / $totalItems) * 100);
    }
    public function userCourses()
{
    return $this->hasMany(UserCourse::class);
}

}
