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
        Schema::create('motors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('model');
            $table->foreignId('branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
            $table->string('registration_number')->unique();
            $table->integer('battery_capacity')->default(100);
            $table->enum('status', ['available', 'in-use', 'maintenance'])->default('available'); // Status sepeda
            $table->timestamp('status_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motors');
    }
};
