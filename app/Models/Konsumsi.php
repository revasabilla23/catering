<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsumsi extends Model
{
    protected $table = 'tb_konsumsi';
    protected $primaryKey = 'IdKonsumsi';
    public $timestamps = false;
    
    // Menyesuaikan dengan kolom yang ada di migrasi
    protected $fillable = [
        'IdUsers',
        'IdShift',
        'IdPesanan', 
        'tanggalKonsumsi',
        'statusQr',   
        'waktuScan',
    ];

    // Relasi ke model User
    public function User()
    {
        return $this->belongsTo(User::class, 'IdUsers', 'IdUsers');
    }

    // Relasi ke model Shift
    public function Shift()
    {
        return $this->belongsTo(Shift::class, 'IdShift', 'IdShift');
    }
    
    // Relasi ke model JadwalPesanan
    public function JadwalPesanan()
    {
        return $this->belongsTo(JadwalPesanan::class, 'IdPesanan', 'IdPesanan');
    }
}
