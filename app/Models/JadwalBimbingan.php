<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalBimbingan extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'tanggal',
        'jam',
        'topik',
        'keterangan',
        'status',
        'catatan_dosen',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}