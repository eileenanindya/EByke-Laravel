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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade'); // Relasi ke tabel rentals
            $table->unsignedBigInteger('user_id'); // Add user_id column
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->date('start_date')->nullable();
            // $table->date('end_date')->nullable();
            // $table->time('start_time')->nullable();
            // $table->time('end_time')->nullable();
            // $table->integer('duration')->nullable();
            // $table->unsignedBigInteger('pickup_branch_id');
            // $table->foreign('pickup_branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
            // $table->string('pickup_branch_name')->nullable();
            // $table->unsignedBigInteger('motor_id');
            // $table->foreign('motor_id')->references('id')->on('motors')->onDelete('cascade');
            $table->enum('payment_method', ['credit_card', 'bank_transfer', 'e-wallet', 'cash'])->default('e-wallet');
            $table->decimal('amount', 10, 2); // Jumlah total yang dibayar
            $table->enum('payment_status', ['success', 'pending', 'failed'])->default('pending'); // Status pembayaran
            $table->time('payment_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
