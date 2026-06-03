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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['truck', 'trailer'])->index();
            $table->string('unit_number')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->enum('status', ['active', 'maintenance', 'out_of_service'])->default('active')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
