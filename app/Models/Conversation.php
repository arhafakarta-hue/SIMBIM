<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'dosen_id',
        'mahasiswa_id',
        'judul',
        'status',
        'rekomendasi_mata_kuliah',
        'catatan_dosen',
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function hasUser(User $user): bool
    {
        return $this->dosen_id === $user->id || $this->mahasiswa_id === $user->id;
    }
}
