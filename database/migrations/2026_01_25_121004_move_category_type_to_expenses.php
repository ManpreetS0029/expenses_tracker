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
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->enum('classification', ['Needs', 'Wants', 'Savings', 'Investments'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['classification']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->enum('type', ['Needs', 'Wants', 'Savings', 'Investments'])->default('Needs');
        });
    }
};
