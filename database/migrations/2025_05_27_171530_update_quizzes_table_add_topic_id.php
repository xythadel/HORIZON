<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('quizzes', function (Blueprint $table) {
        if (Schema::hasColumn('quizzes', 'course_id')) {
            // Drop the foreign key and column safely
            $table->dropConstrainedForeignId('course_id');
        }

        // Add topic_id and link to topics table
        $table->foreignId('topic_id')->after('id')->constrained()->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('quizzes', function (Blueprint $table) {
        $table->dropConstrainedForeignId('topic_id');

        // Re-add course_id
        $table->foreignId('course_id')->after('id')->constrained()->onDelete('cascade');
    });
}
};
