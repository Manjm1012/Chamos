<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_items', function (Blueprint $table) {
            $table->id();
            $table->string('category', 60);
            $table->string('name', 150);
            $table->string('applies_to', 20)->default('both'); // truck, trailer, both
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category', 'applies_to']);
            $table->unique(['category', 'name']);
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->string('category', 60)->nullable()->after('type');
            $table->string('service_item', 150)->nullable()->after('category');
            $table->string('equipment_system', 20)->nullable()->after('service_item'); // truck, trailer, reefer
            $table->decimal('parts_cost', 10, 2)->nullable()->after('cost');
            $table->decimal('labor_cost', 10, 2)->nullable()->after('parts_cost');
            $table->string('vendor', 150)->nullable()->after('performed_by');
            $table->string('invoice_number', 80)->nullable()->after('vendor');
            $table->date('warranty_expiry')->nullable()->after('invoice_number');
        });
    }

    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn([
                'category', 'service_item', 'equipment_system',
                'parts_cost', 'labor_cost', 'vendor',
                'invoice_number', 'warranty_expiry',
            ]);
        });

        Schema::dropIfExists('maintenance_items');
        // unique index dropped with table
    }
};
