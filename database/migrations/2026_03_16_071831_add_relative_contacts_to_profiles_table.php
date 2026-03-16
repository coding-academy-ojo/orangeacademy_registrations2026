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
            $table->string('relative1_name')->after('gpa_value');
            $table->string('relative1_relation')->after('relative1_name');
            $table->string('relative1_phone')->after('relative1_relation');
            $table->string('relative2_name')->nullable()->after('relative1_phone');
            $table->string('relative2_relation')->nullable()->after('relative2_name');
            $table->string('relative2_phone')->nullable()->after('relative2_relation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'relative1_name',
                'relative1_relation',
                'relative1_phone',
                'relative2_name',
                'relative2_relation',
                'relative2_phone'
            ]);
        });
    }
};
