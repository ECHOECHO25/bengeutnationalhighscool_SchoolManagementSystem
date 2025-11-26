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
        Schema::create('student_information', function (Blueprint $table) {
            $table->id();
            $table->string('lrn')->nullable();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('extension')->nullable();
            $table->string('sex')->nullable();
            $table->date('birthdate')->nullable();
            $table->longText('birthplace')->nullable();
            $table->string('religion')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->boolean('is_ips')->default(false);
            $table->string('indigenous')->nullable();
            $table->boolean('is_4ps')->default(false);
            $table->string('four_ps_id_number')->nullable();
            $table->string('building')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('zipcode')->nullable();
            $table->boolean('is_permanent_address')->default(false);
            $table->string('permanent_building')->nullable();
            $table->string('permanent_street')->nullable();
            $table->string('permanent_barangay')->nullable();
            $table->string('permanent_municipality')->nullable();
            $table->string('permanent_province')->nullable();
            $table->string('permanent_zipcode')->nullable();
            $table->string('father_lastname')->nullable();
            $table->string('father_firstname')->nullable();
            $table->string('father_middlename')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('mother_lastname')->nullable();
            $table->string('mother_firstname')->nullable();
            $table->string('mother_middlename')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('guardian_lastname')->nullable();
            $table->string('guardian_firstname')->nullable();
            $table->string('guardian_middlename')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->boolean('is_special_needs')->default(false);
            $table->json('special_needs_a1')->nullable();
            $table->json('special_needs_a2')->nullable();
            $table->string('pwd_id_number')->nullable();
            $table->foreignId('grade_level_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_information');
    }
};
