# Railway Deployment - Auto-Remove Issue Fix

## Problem
Deployment berjalan beberapa menit lalu otomatis ter-remove. Ini terjadi karena:
1. Database SQLite tidak persistent (hilang setelah restart)
2. Health check gagal karena database error
3. Aplikasi crash dan Railway auto-remove deployment yang gagal

## Solution

### 1. Add Railway Volume (Persistent Storage)

Railway perlu volume untuk menyimpan database SQLite agar tidak hilang.

**Via Railway Dashboard:**

1. Buka **Railway Dashboard** > Your Project > **Web Service**
2. Klik tab **Settings**
3. Scroll ke **Volumes**
4. Klik **+ New Volume**
5. Set:
   - **Mount Path**: `/data`
   - **Name**: `simbim-database`
6. Klik **Add**

### 2. Update Environment Variable

Setelah add volume, update `DB_DATABASE`:

Railway Dashboard > Web Service > **Variables**:

```env
DB_DATABASE=/data/database.sqlite
```

**Bukan** `/app/database/database.sqlite` (ini akan hilang setiap restart!)

### 3. Update Start Command

Railway Dashboard > Web Service > **Settings** > **Deploy**:

**Start Command:**
```bash
mkdir -p /data && touch /data/database.sqlite && chmod 775 /data/database.sqlite && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

Ini akan:
- Create database file di volume persistent
- Run migration otomatis setiap start
- Start aplikasi

### 4. Redeploy

Setelah set volume dan update variables:
1. Klik **Deploy** > **Redeploy**
2. Tunggu 2-3 menit
3. Check **Deploy Logs** untuk memastikan tidak ada error

### 5. Seed Data (One Time)

Setelah deployment stabil, seed data demo:

Railway Dashboard > Service > Deployments > **"..."** > **Run Command**:

```bash
php artisan db:seed --force
```

## Alternative: Use MySQL/PostgreSQL

Jika SQLite masih bermasalah, gunakan Railway database addon:

1. Railway Dashboard > **+ New** > **Database** > **PostgreSQL**
2. Railway akan auto-generate connection variables
3. Update `config/database.php` untuk use PostgreSQL
4. Commit dan push

## Checklist

- [ ] Add Railway Volume dengan mount path `/data`
- [ ] Update `DB_DATABASE=/data/database.sqlite`
- [ ] Update Start Command dengan migration
- [ ] Redeploy dan tunggu 3 menit
- [ ] Check Deploy Logs - tidak ada error
- [ ] Test aplikasi - bisa login
- [ ] Run seed command untuk data demo

## Common Errors

### "Database file does not exist"
- ✅ Pastikan volume sudah di-add
- ✅ Pastikan `DB_DATABASE` point ke `/data/` bukan `/app/`
- ✅ Start command harus create file dulu

### "Deployment removed after 5 minutes"
- ✅ Check Deploy Logs untuk error message
- ✅ Pastikan health check `/up` bisa diakses
- ✅ Pastikan tidak ada PHP fatal error

### "502 Bad Gateway"
- ✅ Aplikasi belum selesai start (tunggu 1-2 menit)
- ✅ Check Deploy Logs untuk crash
- ✅ Pastikan `$PORT` variable digunakan

## Verify Deployment

Setelah deployment stabil (tidak removed):

1. Buka domain Railway di browser
2. Seharusnya muncul halaman login (dengan atau tanpa CSS)
3. Coba login dengan:
   - Email: `admin@simbim.test`
   - Password: `password`
4. Jika berhasil login = deployment stabil! ✅

---

**Updated:** 2026-05-15
