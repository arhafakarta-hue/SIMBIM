<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'photo',
        'nim',
        'nidn',
        'prodi',
        'semester',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function conversationsAsDosen()
    {
        return $this->hasMany(Conversation::class, 'dosen_id');
    }

    public function conversationsAsMahasiswa()
    {
        return $this->hasMany(Conversation::class, 'mahasiswa_id');
    }
}