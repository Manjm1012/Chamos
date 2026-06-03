<?php
// No-op migration — duplicate created in error. The real migration is:
// 2026_06_03_000002_add_maintenance_catalog.php
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // intentionally empty
    }

    public function down(): void
    {
        // intentionally empty
    }
};
