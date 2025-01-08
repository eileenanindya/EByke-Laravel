<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Motor extends Model
{
    use HasFactory;
    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'motors';

    // Tentukan kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'model',
        'branch_id',
        'registration_number',
        'battery_capacity',
        'status',
        'status_updated_at',
    ];

    // Relasi dengan model Branch
    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    // Relasi dengan model Rental
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'motor_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'motor_id');
    }
}
