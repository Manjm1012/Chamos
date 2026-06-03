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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('tenants')->insert([
            'name' => 'Main Operations',
            'slug' => 'main-ops',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
        });

        Schema::table('equipment', function (Blueprint $table): void {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->cascadeOnDelete();
        });

        Schema::table('maintenances', function (Blueprint $table): void {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->cascadeOnDelete();
        });

        $defaultTenantId = (int) DB::table('tenants')->where('slug', 'main-ops')->value('id');

        DB::table('users')->whereNull('tenant_id')->update(['tenant_id' => $defaultTenantId]);
        DB::table('equipment')->whereNull('tenant_id')->update(['tenant_id' => $defaultTenantId]);
        DB::table('maintenances')->whereNull('tenant_id')->update(['tenant_id' => $defaultTenantId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('tenant_id');
        });

        Schema::table('equipment', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('tenant_id');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('tenant_id');
        });

        Schema::dropIfExists('tenants');
    }
};
