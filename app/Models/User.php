<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function topicProgress()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Get the quiz progress records for the user.
     */
    public function quizProgress()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Calculate the user's topic completion percentage.
     */
    public function calculateTopicProgress()
    {
        $totalTopics = Topic::count();
        if ($totalTopics === 0) return 0;
        
        $completedTopics = $this->topicProgress()
            ->where('completed', true)
            ->count();
            
        return round(($completedTopics / $totalTopics) * 100);
    }

    /**
     * Calculate the user's quiz completion percentage.
     */
    public function calculateQuizProgress()
    {
        $totalQuizzes = Quiz::count();
        if ($totalQuizzes === 0) return 0;
        
        $completedQuizzes = $this->quizProgress()
            ->where('completed', true)
            ->count();
            
        return round(($completedQuizzes / $totalQuizzes) * 100);
    }

    /**
     * Calculate the user's overall progress
     */
    public function calculateOverallProgress()
    {
        $topicProgress = $this->calculateTopicProgress();
        $quizProgress = $this->calculateQuizProgress();
        
        // Simple average of topic and quiz progress
        return round(($topicProgress + $quizProgress) / 2);
    }
}
