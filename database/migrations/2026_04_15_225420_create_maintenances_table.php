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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->date('date')->index();
            $table->enum('type', ['preventive', 'corrective'])->index();
            $table->unsignedBigInteger('odometer_hours');
            $table->text('description');
            $table->decimal('cost', 12, 2)->default(0);
            $table->string('performed_by')->nullable();
            $table->date('next_maintenance_date')->nullable()->index();
            $table->unsignedBigInteger('next_maintenance_odometer')->nullable()->index();
            $table->timestamps();

            $table->index(['equipment_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
