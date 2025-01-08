<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transactions extends Model
{
    use HasFactory;
    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'transactions';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'rental_id',
        'user_id',
        'payment_method',
        'amount',        // Tambahkan field lain sesuai kebutuhan
        'payment_status',      // Misalnya alamat
        'payment_time',
        // 'start_date', // Tambahkan ini
        // 'end_date',
        // 'pickup_branch_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan model Rental
    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id');
    }

    public function pickupBranch()
    {
        return $this->belongsTo(Branches::class, 'pickup_branch_id');
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class,'motor_id', 'id');
    }

}
