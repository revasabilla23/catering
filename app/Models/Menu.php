<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'tb_menu';
    protected $primaryKey = 'IdMenu';
    public $timestamps = false;
    protected $fillable = [
        "namaMenu",
        "deskMenu",
    ];
    
    public function JadwalPesanan()
    {
        return $this->hasOne(JadwalPesanan::class, 'IdMenu', 'IdMenu');
    }

    public function Konsumsi()
    {
        return $this->hasOne(Konsumsi::class, 'IdMenu', 'IdMenu');
    }
}
