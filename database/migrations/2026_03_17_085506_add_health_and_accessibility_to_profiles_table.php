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
        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('has_accessibility_needs')->default(false)->after('id_number');
            $table->text('accessibility_details')->nullable()->after('has_accessibility_needs');
            $table->boolean('has_illness')->default(false)->after('accessibility_details');
            $table->text('illness_details')->nullable()->after('has_illness');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['has_accessibility_needs', 'accessibility_details', 'has_illness', 'illness_details']);
        });
    }
};
