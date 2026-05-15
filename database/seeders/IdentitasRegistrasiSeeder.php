<?php

namespace Database\Seeders;

use App\Models\IdentitasRegistrasi;
use Illuminate\Database\Seeder;

class IdentitasRegistrasiSeeder extends Seeder
{
    public function run(): void
    {
        IdentitasRegistrasi::updateOrCreate(
            ['nomor_identitas' => '22010001'],
            [
                'nama' => 'Mahasiswa Demo',
                'role' => 'mahasiswa',
                'prodi' => 'Sistem Informasi',
                'kelas' => 'SI-4A',
                'semester' => 4,
                'status' => 'aktif',
                'sudah_digunakan' => false,
            ]
        );

        IdentitasRegistrasi::updateOrCreate(
            ['nomor_identitas' => '1987654321'],
            [
                'nama' => 'Dosen Demo',
                'role' => 'dosen',
                'prodi' => 'Sistem Informasi',
                'kelas' => null,
                'semester' => null,
                'status' => 'aktif',
                'sudah_digunakan' => false,
            ]
        );

        IdentitasRegistrasi::updateOrCreate(
            ['nomor_identitas' => 'ADMIN-SIMBIM'],
            [
                'nama' => 'Admin SIMBIM',
                'role' => 'admin',
                'prodi' => null,
                'kelas' => null,
                'semester' => null,
                'status' => 'aktif',
                'sudah_digunakan' => false,
            ]
        );
    }
}