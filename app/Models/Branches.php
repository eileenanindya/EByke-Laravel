<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branches extends Model
{
    use HasFactory;
    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'branches';

    // Tentukan kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'branch_name',
        'branch_address',
        'latitude',
        'longitude',
    ];

    // Relasi dengan model Motor
    public function motors()
    {
        return $this->hasMany(Motor::class, 'branch_id');
    }

    // Relasi dengan model Rental untuk lokasi penjemputan
    public function pickups()
    {
        return $this->hasMany(Rental::class, 'pickup_branch_id');
    }

    // Relasi dengan model Rental untuk lokasi pengembalian
    public function returns()
    {
        return $this->hasMany(Rental::class, 'return_branch_id');
    }
}
