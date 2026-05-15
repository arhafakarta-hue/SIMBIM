# Deploy SIMBIM ke Railway

Panduan lengkap untuk deploy aplikasi SIMBIM Realtime Laravel ke Railway.app.

## Prasyarat

1. Akun Railway.app (daftar di https://railway.app)
2. GitHub account (untuk connect repository)
3. Git terinstall di komputer Anda

## Langkah 1: Persiapan Repository

### 1.1 Inisialisasi Git (jika belum)

```bash
git init
git add .
git commit -m "Initial commit for Railway deployment"
```

### 1.2 Push ke GitHub

Buat repository baru di GitHub, lalu:

```bash
git remote add origin https://github.com/username/simbim-realtime-laravel.git
git branch -M main
git push -u origin main
```

## Langkah 2: Setup di Railway

### 2.1 Buat Project Baru

1. Login ke https://railway.app
2. Klik **"New Project"**
3. Pilih **"Deploy from GitHub repo"**
4. Pilih repository `simbim-realtime-laravel`
5. Railway akan otomatis detect sebagai PHP project

### 2.2 Buat Service untuk Web App

Railway akan otomatis membuat service pertama. Ini akan menjadi web app utama.

### 2.3 Buat Service untuk Reverb (WebSocket)

1. Di project Railway, klik **"+ New"**
2. Pilih **"GitHub Repo"** (pilih repo yang sama)
3. Beri nama service: **"reverb"**

## Langkah 3: Konfigurasi Environment Variables

### 3.1 Service Web App

Klik service web app, lalu masuk ke tab **"Variables"**, tambahkan:

```env
APP_NAME=SIMBIM Realtime
APP_ENV=production
APP_KEY=base64:GENERATE_THIS_LATER
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://your-app.railway.app

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

BROADCAST_CONNECTION=reverb
BROADCAST_DRIVER=reverb

REVERB_APP_ID=simbim-app
REVERB_APP_KEY=simbim-key
REVERB_APP_SECRET=simbim-secret
REVERB_HOST=your-reverb-service.railway.app
REVERB_PORT=443
REVERB_SCHEME=https
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080
```

**PENTING**: Ganti `your-app.railway.app` dan `your-reverb-service.railway.app` dengan domain Railway Anda nanti.

### 3.2 Service Reverb

Klik service reverb, lalu masuk ke tab **"Variables"**, tambahkan environment variables yang sama seperti di atas.

## Langkah 4: Konfigurasi Build & Deploy

### 4.1 Web App Service

1. Masuk ke tab **"Settings"**
2. Di bagian **"Deploy"**, pastikan:
   - **Build Command**: (kosongkan, nixpacks akan handle)
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
3. Di bagian **"Networking"**:
   - Klik **"Generate Domain"** untuk mendapatkan public URL

### 4.2 Reverb Service

1. Masuk ke tab **"Settings"**
2. Di bagian **"Deploy"**:
   - **Start Command**: `php artisan reverb:start --host=0.0.0.0 --port=$PORT`
3. Di bagian **"Networking"**:
   - Klik **"Generate Domain"** untuk mendapatkan public URL untuk WebSocket

## Langkah 5: Generate APP_KEY

Setelah deployment pertama selesai:

1. Buka service web app
2. Klik tab **"Deployments"**
3. Klik deployment terbaru, lalu klik **"View Logs"**
4. Atau gunakan Railway CLI:

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link project
railway link

# Generate key
railway run php artisan key:generate --show
```

5. Copy APP_KEY yang dihasilkan
6. Update variable `APP_KEY` di kedua service (web app dan reverb)

## Langkah 6: Setup Database

Railway akan otomatis membuat file SQLite, tapi kita perlu run migration:

```bash
# Menggunakan Railway CLI
railway run php artisan migrate --seed --force
```

Atau melalui Railway Dashboard:
1. Klik service web app
2. Klik tab **"Deployments"**
3. Klik **"..."** pada deployment aktif
4. Pilih **"Run Command"**
5. Jalankan: `php artisan migrate --seed --force`

## Langkah 7: Update Environment Variables dengan Domain

Setelah mendapat domain dari Railway:

1. Copy domain web app (misal: `simbim-production.up.railway.app`)
2. Copy domain reverb (misal: `simbim-reverb.up.railway.app`)
3. Update variables di kedua service:
   - `APP_URL=https://simbim-production.up.railway.app`
   - `REVERB_HOST=simbim-reverb.up.railway.app`

4. Redeploy kedua service

## Langkah 8: Testing

1. Buka `https://your-app.railway.app`
2. Login dengan akun demo:
   - Email: `admin@simbim.test`
   - Password: `password`
3. Test fitur chat untuk memastikan WebSocket berfungsi

## Troubleshooting

### WebSocket tidak connect

1. Pastikan `REVERB_HOST` mengarah ke domain reverb service
2. Pastikan `REVERB_PORT=443` dan `REVERB_SCHEME=https`
3. Check logs di service reverb

### Database error

1. Pastikan path database: `/app/database/database.sqlite`
2. Run migration ulang: `railway run php artisan migrate:fresh --seed --force`

### 500 Error

1. Set `APP_DEBUG=true` sementara untuk lihat error
2. Check logs: `railway logs`
3. Pastikan `APP_KEY` sudah di-generate

## Monitoring

- **Logs**: Railway Dashboard > Service > Logs
- **Metrics**: Railway Dashboard > Service > Metrics
- **CLI**: `railway logs -f` (follow logs)

## Custom Domain (Opsional)

1. Beli domain di provider (Namecheap, Cloudflare, dll)
2. Di Railway service settings > Networking
3. Klik **"Custom Domain"**
4. Tambahkan domain Anda
5. Update DNS records sesuai instruksi Railway
6. Update `APP_URL` dan `REVERB_HOST` di environment variables

## Biaya

Railway menyediakan:
- **Free tier**: $5 credit per bulan
- **Hobby plan**: $5/bulan untuk usage tambahan
- Aplikasi ini estimasi menggunakan ~$3-5/bulan

## Catatan Penting

1. **SQLite di Railway**: File database akan hilang saat redeploy. Untuk production, pertimbangkan:
   - Railway PostgreSQL plugin
   - External database (PlanetScale, Supabase)

2. **File Storage**: Session dan cache file-based akan reset saat redeploy. Pertimbangkan:
   - Redis untuk session/cache
   - Railway Redis plugin

3. **Reverb Scaling**: Reverb service harus selalu running untuk WebSocket. Pastikan tidak sleep.

## Alternatif: Deploy dengan Railway CLI

```bash
# Install CLI
npm i -g @railway/cli

# Login
railway login

# Init project
railway init

# Deploy
railway up

# Set variables
railway variables set APP_NAME="SIMBIM Realtime"
railway variables set APP_ENV=production
# ... dst

# Run migrations
railway run php artisan migrate --seed --force
```

## Support

Jika ada masalah:
- Railway Docs: https://docs.railway.app
- Railway Discord: https://discord.gg/railway
- GitHub Issues: Repository ini

---

**Selamat! Aplikasi SIMBIM Anda sekarang live di Railway! 🚀**
