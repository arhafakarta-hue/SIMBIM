<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nidn',
        'nama_lengkap',
        'prodi',
        'bidang_keahlian',
        'no_hp',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}