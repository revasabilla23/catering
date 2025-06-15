<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'tb_shift';
    protected $primaryKey = 'IdShift';
    public $timestamps = false;

    protected $fillable = [
        'namaShift',
        'start',
        'end',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'IdShift', 'IdShift');
    }

    public function JadwalPesanan()
    {
        return $this->hasOne(JadwalPesanan::class, 'IdShift', 'IdShift');
    }

    public function Konsumsi()
    {
        return $this->hasOne(Konsumsi::class, 'IdShift', 'IdShift');
    }
}