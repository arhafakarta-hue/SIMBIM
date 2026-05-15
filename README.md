# SIMBIM Realtime Laravel

SIMBIM adalah contoh aplikasi Sistem Informasi Bimbingan Akademik sederhana berbasis Laravel dengan chat real-time antar dosen wali dan mahasiswa memakai Laravel Reverb.

## Fitur

- Login multi role: admin, dosen, mahasiswa.
- Dashboard sederhana sesuai role.
- Daftar ruang bimbingan.
- Chat dosen dan mahasiswa.
- Pesan masuk real-time antar laptop selama memakai alamat server yang sama.
- Fallback polling otomatis jika WebSocket belum tersambung.
- Menggunakan SQLite agar lebih mudah dijalankan tanpa membuat database MySQL dulu.

## Akun Demo

Password semua akun: `password`

| Role | Email |
|---|---|
| Admin | admin@simbim.test |
| Dosen Wali | dosen@simbim.test |
| Mahasiswa | mahasiswa@simbim.test |

## Cara Menjalankan

Buka terminal di folder project, lalu jalankan:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

Lalu buka dua terminal tambahan.

Terminal 1 untuk server Laravel:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Terminal 2 untuk Reverb/WebSocket:

```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```

Buka aplikasi di browser:

```text
http://127.0.0.1:8000
```

Untuk beda laptop dalam WiFi yang sama, pakai IP laptop yang menjadi server, misalnya:

```text
http://192.168.1.10:8000
```

Lalu ubah `.env` bagian ini agar WebSocket mengarah ke IP laptop server:

```env
APP_URL=http://192.168.1.10:8000
REVERB_HOST=192.168.1.10
REVERB_PORT=8080
REVERB_SCHEME=http
```

Setelah mengubah `.env`, jalankan:

```bash
php artisan config:clear
```

## Catatan Penting

Project ini sengaja dibuat ringan tanpa React/Vue dan tanpa npm build. Halaman chat memakai JavaScript bawaan browser untuk koneksi WebSocket ke Laravel Reverb. Kalau nanti ingin dibuat lebih standar, bisa ditambah Laravel Echo dan Vite.

## Deploy ke Production

### Railway.app (Recommended)

Aplikasi ini sudah siap deploy ke Railway.app dengan konfigurasi yang sudah disiapkan.

**Quick Start:**
- Baca [RAILWAY_QUICKSTART.md](RAILWAY_QUICKSTART.md) untuk panduan cepat
- Baca [DEPLOY_RAILWAY.md](DEPLOY_RAILWAY.md) untuk panduan lengkap

**File deployment yang sudah disiapkan:**
- `railway.json` - Railway configuration
- `nixpacks.toml` - Build settings
- `Procfile` - Process definitions
- `.env.example` - Environment template (sudah disesuaikan untuk production)

**Estimasi biaya:** $3-5/bulan di Railway
