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
        Schema::create('professional_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('user_uuid')->unique();
            $table->string('job_title')->nullable();
            $table->string('company_name')->nullable();
            $table->string('skills')->nullable();
            $table->string('years_of_experience')->nullable();
            $table->string('certifications')->nullable();
            $table->string('social_links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_profiles');
    }
};
