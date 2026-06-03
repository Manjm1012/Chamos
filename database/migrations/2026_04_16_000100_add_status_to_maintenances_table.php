<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('maintenances', function (Blueprint $table): void {
            $table->string('status', 20)
                ->default('pending')
                ->index();
        });

        // Backfill historical records as completed when they are in the past.
        DB::table('maintenances')
            ->whereDate('date', '<=', now()->toDateString())
            ->update(['status' => 'completed']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table): void {
            $table->dropColumn('status');
        });
    }
};
