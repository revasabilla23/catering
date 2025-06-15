<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrToken extends Model
{
    protected $table = 'tb_qrToken';
    protected $primaryKey = 'IdQrToken';
    public $timestamps = false;

    protected $fillable = [
        'IdUsers',
        'token',
        'create',
        'expired',
    ];

    protected $dates = ['create', 'expired'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'IdUsers', 'IdUsers');
    }

    // Cek apakah token sudah expired
    public function isExpired(): bool
    {
        return now()->greaterThan($this->expired);
    }
}
