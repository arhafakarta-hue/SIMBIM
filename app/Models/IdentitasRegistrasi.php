<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentitasRegistrasi extends Model
{
    protected $fillable = [
        'nomor_identitas',
        'nama',
        'role',
        'prodi',
        'kelas',
        'semester',
        'status',
        'sudah_digunakan',
    ];
}