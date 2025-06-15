<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Shift; 
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    // Gunakan nama tabel khusus
    protected $table = 'tb_users';

    // Primary key bukan 'id'
    protected $primaryKey = 'IdUsers';

    // Tidak ada timestamp default created_at/updated_at
    public $timestamps = false;

    // Field yang bisa diisi
    protected $fillable = [
        'email',
        'password',
        'role',
        'IdShift',
        'statusUsers',
        'create_at',
    ];


    // Field yang harus disembunyikan (misalnya untuk API response)
    protected $hidden = [
        'password',
    ];

    // Konversi tipe data otomatis
    protected $casts = [
        'create_at' => 'datetime',
    ];

    // JWT identifier
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // JWT custom claims
    public function getJWTCustomClaims()
    {
        return [];
    }

    // (Opsional) Relasi ke tabel shift, jika ada
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'IdShift');
    }

    public function Konsumsi()
    {
        return $this->hasOne(Konsumsi::class, 'IdUsers', 'IdUsers');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'IdUsers', 'IdUsers');
    }
}
