<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('maintenance_items')) {
            return;
        }

        // Remove any duplicate (category, name) rows before adding unique constraint
        // Wrapped in subquery alias to avoid MySQL 1093 error
        DB::statement(
            'DELETE FROM maintenance_items WHERE id NOT IN (
                SELECT id FROM (
                    SELECT MIN(id) as id FROM maintenance_items GROUP BY category, name
                ) AS tmp
            )'
        );

        try {
            Schema::table('maintenance_items', function (Blueprint $table) {
                $table->unique(['category', 'name'], 'maintenance_items_category_name_unique');
            });
        } catch (\Exception $e) {
            // Index already exists (created by migration 002) — skip silently
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('maintenance_items')) {
            Schema::table('maintenance_items', function (Blueprint $table) {
                $table->dropUnique('maintenance_items_category_name_unique');
            });
        }
    }
};
