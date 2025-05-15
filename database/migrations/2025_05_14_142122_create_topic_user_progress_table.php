<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('topic_user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('progress_percentage')->default(0); // 0–100
            $table->timestamps();

            $table->unique(['user_id', 'course_id']); // Prevent duplicate progress records
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_user_progress');
    }
};
