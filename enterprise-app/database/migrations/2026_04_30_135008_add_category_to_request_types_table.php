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
        if (!Schema::hasTable('requests_type')) {
            return;
        }

        if (Schema::hasColumn('requests_type', 'category')) {
            return;
        }

        Schema::table('requests_type', function (Blueprint $table) {
            $table->string('category')->default('date')->after('Name_Type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('requests_type')) {
            return;
        }

        Schema::table('requests_type', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
