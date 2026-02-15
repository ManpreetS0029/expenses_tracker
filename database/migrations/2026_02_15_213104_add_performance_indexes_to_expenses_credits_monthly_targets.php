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
        Schema::table('expenses', function (Blueprint $table) {
            $table->index(['user_id', 'type', 'date']);
            $table->index(['user_id', 'date']);
            $table->index(['user_id', 'category_id']);
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->index(['user_id', 'date']);
            $table->index(['user_id', 'category_id']);
        });

        Schema::table('monthly_targets', function (Blueprint $table) {
            $table->index(['user_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'type', 'date']);
            $table->dropIndex(['user_id', 'date']);
            $table->dropIndex(['user_id', 'category_id']);
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'date']);
            $table->dropIndex(['user_id', 'category_id']);
        });

        Schema::table('monthly_targets', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'month']);
        });
    }
};
