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
        Schema::table('skill_tests', function (Blueprint $table) {
            $table->unsignedBigInteger('topic_id')->after('id');
            $table->integer('score')->default(10)->after('expected_output');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skill_tests', function (Blueprint $table) {
            //
        });
    }
};
