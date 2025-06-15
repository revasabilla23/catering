<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPesanan extends Model
{
    protected $table = "tb_jadwalPesanan";
    protected $primaryKey = "IdPesanan";
    public $timestamps = false;
    protected $fillable = [
        "IdShift",
        'IdMenu',
        "tanggalPesanan",
        "JumlahPesanan",
        "statusPesanan",
        "VerifAt",
    ];

    public function Shift()
    {
        return $this->belongsTo(Shift::class,'IdShift', 'IdShift');
    }

    public function Menu()
    {
        return $this->belongsTo(Menu::class,'IdMenu', 'IdMenu');
    }
    
    public function Konsumsi()
    {
        return $this->hasMany(Konsumsi::class, 'IdPesanan', 'IdPesanan');
    }
}
