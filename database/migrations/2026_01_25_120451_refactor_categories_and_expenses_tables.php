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
        // Drop sub_category_id from expenses
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['sub_category_id']);
            $table->dropColumn('sub_category_id');
        });

        // Modify categories table
        Schema::table('categories', function (Blueprint $table) {
            // Drop parent_id foreign key and column
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');

            // Add static type column
            $table->enum('type', ['Needs', 'Wants', 'Savings', 'Investments'])->default('Needs');
        });

        // Delete all categories that were subcategories (had parent_id not null) if we could,
        // but since we dropped the column, they are just orphan categories now.
        // That is acceptable for a dev reset.
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->foreignId('parent_id')->nullable()->constrained('categories')->cascadeOnDelete();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('sub_category_id')->nullable()->constrained('categories')->nullOnDelete();
        });
    }
};
