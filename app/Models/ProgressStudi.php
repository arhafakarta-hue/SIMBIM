<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressStudi extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'semester',
        'ip',
        'ipk',
        'sks_lulus',
        'file_khs',
        'catatan_mahasiswa',
        'catatan_dosen',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}