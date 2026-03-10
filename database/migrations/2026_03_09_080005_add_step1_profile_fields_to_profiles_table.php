<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('second_name_en', 100)->nullable()->after('first_name_en');
            $table->string('third_name_en', 100)->nullable()->after('second_name_en');
            $table->string('second_name_ar', 100)->nullable()->after('first_name_ar');
            $table->string('third_name_ar', 100)->nullable()->after('second_name_ar');
            $table->string('neighborhood', 100)->nullable()->after('city');
            $table->string('university', 150)->nullable()->after('education_level');
            $table->boolean('is_graduated')->nullable()->after('field_of_study');
            $table->string('graduation_year', 4)->nullable()->after('is_graduated');
            $table->string('expected_graduation_year', 4)->nullable()->after('graduation_year');
            $table->enum('gpa_type', ['percentage', 'gpa_4', 'grade'])->nullable()->after('expected_graduation_year');
            $table->string('gpa_value', 20)->nullable()->after('gpa_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'second_name_en',
                'third_name_en',
                'second_name_ar',
                'third_name_ar',
                'neighborhood',
                'university',
                'is_graduated',
                'graduation_year',
                'expected_graduation_year',
                'gpa_type',
                'gpa_value',
            ]);
        });
    }
};
