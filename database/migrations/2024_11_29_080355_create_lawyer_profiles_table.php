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
        Schema::create('lawyer_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('bar_certificate')->nullable();
            $table->string('bar_association')->nullable();
            $table->string('license_number')->nullable();
            $table->string('license_issue_date')->nullable();
            $table->string('license_expiry_data')->nullable();
            $table->string('pratice_areas')->nullable();
            $table->string('years_of_experience')->nullable();
            $table->string('law_firm')->nullable();
            $table->string('status')->default('Pending');
            $table->string('availability')->nullable();
            $table->string('graduation_year')->nullable();
            $table->string('professional_bio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyer_profiles');
    }
};
