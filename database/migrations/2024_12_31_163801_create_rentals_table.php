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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');// Add user_id column
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('motor_id');
            $table->foreign('motor_id')->references('id')->on('motors')->onDelete('cascade');
            // $table->foreignId('motor_id')->constrained('motors')->onDelete('cascade'); // Referensi ke tabel motor
            
            $table->unsignedBigInteger('pickup_branch_id'); // Referensi ke branches
            $table->unsignedBigInteger('return_branch_id'); // Referensi ke branches

            // Definisi foreign key
            $table->foreign('pickup_branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
            $table->foreign('return_branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration')->nullable(); // Durasi sewa (dalam menit/jam)
            $table->decimal('total_cost', 10, 2)->nullable(); // Biaya total (format decimal)
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active'); // Status pemesanan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
