<?php

namespace Database\Seeders;

use App\Models\IdentitasRegistrasi;
use Illuminate\Database\Seeder;

class TambahIdentitasSeeder extends Seeder
{
    public function run(): void
    {
        IdentitasRegistrasi::updateOrCreate(
            ['nomor_identitas' => '22010002'],
            [
                'nama' => 'Nama Mahasiswa Baru',
                'role' => 'mahasiswa',
                'prodi' => 'Sistem Informasi',
                'kelas' => 'SI-4A',
                'semester' => 4,
                'status' => 'aktif',
                'sudah_digunakan' => false,
            ]
        );

        IdentitasRegistrasi::updateOrCreate(
            ['nomor_identitas' => '1987654322'],
            [
                'nama' => 'Nama Dosen Baru',
                'role' => 'dosen',
                'prodi' => 'Sistem Informasi',
                'kelas' => null,
                'semester' => null,
                'status' => 'aktif',
                'sudah_digunakan' => false,
            ]
        );
    }
}