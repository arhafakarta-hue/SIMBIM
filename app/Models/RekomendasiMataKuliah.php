<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekomendasiMataKuliah extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'kode_mata_kuliah',
        'nama_mata_kuliah',
        'sks',
        'semester_rekomendasi',
        'status',
        'alasan_rekomendasi',
        'catatan_tambahan',
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