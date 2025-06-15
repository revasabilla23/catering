<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'tb_logs';
    protected $primaryKey = 'IdLogs';
    public $timestamps = false;

    protected $fillable = [
        'IdUser',
        'IdQrToken',
        'statusLogs',
        'reason',
        'scannedBy',
        'scannedAt',
    ];

    protected $casts = [
        'statusLogs' => 'boolean',
        'scannedAt' => 'datetime',
    ];

    // Relasi ke User yang di-scan
    public function user()
    {
        return $this->belongsTo(User::class, 'IdUser', 'IdUsers');
    }

    // Relasi ke QR token
    public function qrToken()
    {
        return $this->belongsTo(QrToken::class, 'IdQrToken', 'IdQrToken');
    }

    // Relasi ke User yang melakukan scan
    public function scanner()
    {
        return $this->belongsTo(User::class, 'scannedBy', 'IdUsers');
    }
}
