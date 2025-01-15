<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Battery_stations extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'battery_stations';

    // Tentukan kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'stations_name',
        'stations_address',
        'latitude',
        'longitude',
        'total_slots', // Total kapasitas pengisian (jumlah slot)
        'available_slots',   // Slot yang tidak terpakai
    ];
}
