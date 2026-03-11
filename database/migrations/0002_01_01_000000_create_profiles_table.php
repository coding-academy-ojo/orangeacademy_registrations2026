<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            // English Names
            $table->string('first_name_en', 100)->nullable();
            $table->string('second_name_en', 100)->nullable();
            $table->string('third_name_en', 100)->nullable();
            $table->string('last_name_en', 100)->nullable();

            // Arabic Names
            $table->string('first_name_ar', 100)->nullable();
            $table->string('second_name_ar', 100)->nullable();
            $table->string('third_name_ar', 100)->nullable();
            $table->string('last_name_ar', 100)->nullable();

            // Contact & Demographics
            $table->string('phone', 20)->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->text('address')->nullable();

            // Education
            $table->string('education_level', 100)->nullable();
            $table->string('university', 150)->nullable();
            $table->string('field_of_study', 100)->nullable();
            $table->string('major')->nullable();
            $table->boolean('is_graduated')->nullable();
            $table->string('graduation_year', 4)->nullable();
            $table->string('expected_graduation_year', 4)->nullable();
            $table->enum('gpa_type', ['percentage', 'gpa_4', 'grade'])->nullable();
            $table->string('gpa_value', 20)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
