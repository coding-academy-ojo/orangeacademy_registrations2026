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
            $table->string('relative1_name')->nullable()->change();
            $table->string('relative1_relation')->nullable()->change();
            $table->string('relative1_phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('relative1_name')->nullable(false)->change();
            $table->string('relative1_relation')->nullable(false)->change();
            $table->string('relative1_phone')->nullable(false)->change();
        });
    }
};
