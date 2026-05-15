<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@simbim.test'],
            [
                'name' => 'Admin SIMBIM',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'prodi' => 'Sistem Informasi',
            ]
        );

        $dosen = User::firstOrCreate(
            ['email' => 'dosen@simbim.test'],
            [
                'name' => 'Bu Rani Dosen Wali',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'nidn' => '1122334455',
                'prodi' => 'Sistem Informasi',
            ]
        );

        $mahasiswa = User::firstOrCreate(
            ['email' => 'mahasiswa@simbim.test'],
            [
                'name' => 'Nio Mahasiswa',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'nim' => '230001001',
                'prodi' => 'Sistem Informasi',
                'semester' => 4,
            ]
        );

        $conversation = Conversation::firstOrCreate(
            [
                'dosen_id' => $dosen->id,
                'mahasiswa_id' => $mahasiswa->id,
            ],
            [
                'judul' => 'Bimbingan KRS Semester Genap',
                'status' => 'aktif',
                'rekomendasi_mata_kuliah' => 'Ambil maksimal 20 SKS dulu, prioritaskan mata kuliah wajib dan ulangi mata kuliah yang nilainya masih kurang.',
                'catatan_dosen' => 'Mahasiswa perlu konsultasi rutin sebelum finalisasi KRS.',
            ]
        );

        if ($conversation->messages()->count() === 0) {
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $mahasiswa->id,
                'message' => 'Selamat pagi Bu, saya mau konsultasi tentang pengambilan mata kuliah semester ini.',
            ]);

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $dosen->id,
                'message' => 'Selamat pagi. Boleh, silakan kirim rencana KRS kamu dulu ya.',
            ]);
        }
    }
}
