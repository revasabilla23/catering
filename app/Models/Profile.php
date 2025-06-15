<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'tb_profile';
    protected $primaryKey = 'IdProfile';
    public $timestamps = false;

    protected $fillable = [
        'IdUsers',
        'name',
        'gender',
        'nik',
        'tanggalLahir',
        'address',
        'foto',
        'noTelepon',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'IdUsers', 'IdUsers');
    }
}
