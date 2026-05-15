# Railway Deployment - CSS Not Loading Fix

## Problem
CSS tidak muncul di Railway deployment karena `APP_URL` tidak di-set dengan benar.

## Solution

### 1. Set APP_URL di Railway Dashboard

**Untuk Web Service:**

1. Buka **Railway Dashboard** > Your Project > **Web Service**
2. Klik tab **Settings**
3. Scroll ke **Domains** - copy domain yang di-generate Railway (contoh: `simbim-production-abc123.up.railway.app`)
4. Klik tab **Variables**
5. Edit atau tambah variable `APP_URL`:
   ```
   APP_URL=https://simbim-production-abc123.up.railway.app
   ```
   ⚠️ **PENTING**: Gunakan `https://` (bukan `http://`) dan domain LENGKAP dari Railway

6. Klik **Save** atau **Add Variable**
7. Railway akan auto-redeploy

### 2. Verify CSS File Exists

File CSS harus ada di repository:
- Path: `public/style.css`
- Size: ~21KB
- Status: ✅ Committed to git

### 3. Clear Config Cache (Optional)

Jika masih tidak muncul setelah set `APP_URL`, run command ini via Railway Dashboard:

1. Railway Dashboard > Service > Deployments
2. Klik deployment yang aktif > **"..."** menu
3. Pilih **"Run Command"**
4. Jalankan:
   ```bash
   php artisan config:clear && php artisan config:cache
   ```

### 4. Check Browser Console

Buka aplikasi di browser, tekan `F12` untuk buka Developer Tools, lalu:

1. Tab **Console** - lihat ada error?
2. Tab **Network** - filter "CSS" - lihat status code:
   - ✅ **200 OK** = CSS loaded successfully
   - ❌ **404 Not Found** = CSS file tidak ditemukan (check APP_URL)
   - ❌ **500 Error** = Server error (check logs)

### 5. Expected CSS URL

Setelah set `APP_URL`, CSS URL seharusnya:
```
https://your-domain.railway.app/style.css
```

Bukan:
```
http://localhost/style.css  ❌
http://192.168.x.x/style.css  ❌
```

## Quick Checklist

- [ ] `APP_URL` di-set di Railway Variables dengan `https://` dan domain lengkap
- [ ] Railway sudah redeploy setelah set `APP_URL`
- [ ] File `public/style.css` ada di repository (21KB)
- [ ] Browser console tidak ada error 404 untuk style.css
- [ ] Clear browser cache (Ctrl+Shift+R atau Cmd+Shift+R)

## Still Not Working?

### Check Railway Logs

1. Railway Dashboard > Service > Deployments
2. Klik deployment aktif > **Deploy Logs**
3. Cari error terkait file serving atau public directory

### Verify Public Directory

Run command via Railway:
```bash
ls -la /app/public/
```

Output seharusnya menunjukkan `style.css` ada.

### Force Asset URL (Last Resort)

Jika masih gagal, edit `resources/views/layouts/app.blade.php` line 8:

**Dari:**
```blade
<link rel="stylesheet" href="{{ asset('style.css') }}">
```

**Ke:**
```blade
<link rel="stylesheet" href="{{ env('APP_URL') }}/style.css">
```

Lalu commit dan push.

---

**Updated:** 2026-05-15
