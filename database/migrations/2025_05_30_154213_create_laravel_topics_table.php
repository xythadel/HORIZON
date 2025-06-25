<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaravelTopicsTable extends Migration
{
    public function up(): void
    {
        Schema::create('laravel_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                  ->constrained('courses')  // References the shared courses table
                  ->onDelete('cascade');
            $table->string('title');
            $table->string('module_name')->nullable();
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laravel_topics');
    }
}
