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
        Schema::create('battery_stations', function (Blueprint $table) {
            $table->id();
            $table->string('station_name'); // Nama stasiun
            $table->string('station_address'); // Lokasi (alamat lengkap)
            $table->float('latitude')->nullable(); // Koordinat untuk peta (latitude)
            $table->float('longitude')->nullable(); // Koordinat untuk peta (longitude)
            $table->integer('total_slots'); // Kapasitas pengisian (jumlah slot baterai)
            $table->integer('available_slots')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battery_stations');
    }
};
