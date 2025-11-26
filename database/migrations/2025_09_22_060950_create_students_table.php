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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('lrn')->nullable();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->boolean('is_senior_high')->nullable();
            $table->string('track')->nullable();
            $table->string('strand')->nullable();
            $table->foreignId('school_year_id');
            $table->foreignId('grade_level_id');
            $table->foreignId('user_id');
            $table->foreignId('student_information_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
