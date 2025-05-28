<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Quiz extends Model
    {
        use HasFactory;
        
        protected $fillable = ['title', 'description', 'is_published','course_id', 'topic_id'];
        
        public function questions()
        {
            return $this->hasMany(Question::class);
        }

        public function topic()
        {
            return $this->belongsTo(Topic::class);
        }
        public function course()
        {
        return $this->belongsTo(Course::class);
    }
         function quiz()
        {
         $this->belongsTo(Quiz::class);
        }
        public function quizzes()
    {
        return $this->hasMany(Quiz::class);
        }


    }