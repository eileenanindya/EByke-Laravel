<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rental extends Model
{
    use HasFactory;
    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'rentals';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'user_id',
        'motor_id',
        'pickup_branch_id',
        'return_branch_id', 
        'start_date',
        'end_date', 
        'start_time',        
        'end_time',          
        'duration',         
        'total_cost',        
        'status', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan model Motor
    public function motor()
    {
        return $this->belongsTo(Motor::class,'motor_id', 'id');
    }

    // Relasi dengan model Branch untuk lokasi pengambilan
    public function pickupBranch()
    {
        return $this->belongsTo(Branches::class, 'pickup_branch_id', 'branch_id');
    }

    // Relasi dengan model Branch untuk lokasi pengembalian
    public function returnBranch()
    {
        return $this->belongsTo(Branches::class, 'return_branch_id', 'branch_id');
    }

    // Relasi dengan model Transaction (jika ada transaksi terkait rental ini)
    public function transaction()
    {
        return $this->hasOne(Transactions::class, 'rental_id');
    }
}
