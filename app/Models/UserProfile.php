<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'user_profiles';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phonenum',        // Tambahkan field lain sesuai kebutuhan
        'address',      // Misalnya alamat
    ];

    // Definisikan relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
