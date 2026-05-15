# Quick Start - Railway Deployment

## Ringkasan Cepat

File-file yang sudah disiapkan untuk Railway deployment:
- ✅ `railway.json` - Konfigurasi Railway
- ✅ `nixpacks.toml` - Build configuration
- ✅ `Procfile` - Process definitions
- ✅ `.env.example` - Environment variables template
- ✅ `DEPLOY_RAILWAY.md` - Panduan lengkap deployment

## Deploy dalam 5 Langkah

### 1. Push ke GitHub

```bash
git init
git add .
git commit -m "Ready for Railway deployment"
git remote add origin https://github.com/USERNAME/REPO.git
git branch -M main
git push -u origin main
```

### 2. Buat Project di Railway

1. Buka https://railway.app
2. Login dengan GitHub
3. Klik **"New Project"**
4. Pilih **"Deploy from GitHub repo"**
5. Pilih repository Anda

### 3. Buat 2 Services

**Service 1: Web App**
- Railway akan auto-create ini
- Generate domain di Settings > Networking

**Service 2: Reverb (WebSocket)**
- Klik "+ New" > "GitHub Repo" (pilih repo yang sama)
- Nama: "reverb"
- Settings > Start Command: `php artisan reverb:start --host=0.0.0.0 --port=$PORT`
- Generate domain di Settings > Networking

### 4. Set Environment Variables

Copy variables dari `.env.example` ke kedua services, lalu update:

```env
APP_URL=https://your-web-domain.railway.app
REVERB_HOST=your-reverb-domain.railway.app
REVERB_PORT=443
REVERB_SCHEME=https
```

### 5. Generate APP_KEY & Run Migration

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login & link
railway login
railway link

# Generate key
railway run php artisan key:generate --show

# Update APP_KEY di Railway dashboard, lalu:
railway run php artisan migrate --seed --force
```

## Akses Aplikasi

Buka domain web app Anda: `https://your-app.railway.app`

**Login:**
- Email: `admin@simbim.test`
- Password: `password`

## Troubleshooting

**WebSocket tidak connect?**
- Pastikan REVERB_HOST, REVERB_PORT, REVERB_SCHEME sudah benar
- Check logs service reverb

**Database error?**
- Run: `railway run php artisan migrate:fresh --seed --force`

**500 Error?**
- Check logs: `railway logs`
- Pastikan APP_KEY sudah di-set

## Dokumentasi Lengkap

Baca [DEPLOY_RAILWAY.md](DEPLOY_RAILWAY.md) untuk panduan detail.

---

**Estimasi waktu deployment: 15-20 menit** ⏱️
