# Railway Build Troubleshooting Guide

## Error: "Failed for build images"

Jika Anda mendapat error ini, ikuti langkah-langkah berikut:

### Solusi 1: Gunakan Nixpacks Sederhana

File `nixpacks.toml` sudah diupdate dengan konfigurasi minimal yang lebih stabil.

**Push perubahan ke GitHub:**

```bash
git add .
git commit -m "Fix Railway build configuration"
git push origin main
```

Railway akan otomatis rebuild setelah push.

### Solusi 2: Konfigurasi Manual di Railway Dashboard

Jika masih gagal, set manual di Railway:

#### Untuk Service Web App:

1. Buka Railway Dashboard > Service Web App
2. Masuk ke tab **Settings**
3. Di bagian **Build**:
   - **Build Command**: `bash build.sh`
   - Atau: `composer install --no-dev --optimize-autoloader && mkdir -p database && touch database/database.sqlite`
4. Di bagian **Deploy**:
   - **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`
5. Klik **Deploy** untuk rebuild

#### Untuk Service Reverb:

1. Buka Railway Dashboard > Service Reverb
2. Masuk ke tab **Settings**
3. Di bagian **Build**:
   - **Build Command**: `bash build.sh`
   - Atau: `composer install --no-dev --optimize-autoloader && mkdir -p database && touch database/database.sqlite`
4. Di bagian **Deploy**:
   - **Start Command**: `php artisan reverb:start --host=0.0.0.0 --port=$PORT`
5. Klik **Deploy** untuk rebuild

### Solusi 3: Hapus nixpacks.toml dan railway.json

Jika masih error, coba biarkan Railway auto-detect:

```bash
# Backup files
mv nixpacks.toml nixpacks.toml.bak
mv railway.json railway.json.bak

# Push
git add .
git commit -m "Let Railway auto-detect build"
git push origin main
```

Lalu set manual di Railway Dashboard seperti Solusi 2.

### Solusi 4: Check PHP Extensions

Pastikan semua PHP extensions yang dibutuhkan tersedia. Di Railway Dashboard:

1. Service Settings > Variables
2. Tambahkan variable:
   ```
   NIXPACKS_PHP_EXTENSIONS=pdo,pdo_sqlite,sqlite3,mbstring,xml,curl,zip,bcmath
   ```

### Solusi 5: Verbose Build Logs

Untuk melihat detail error:

1. Railway Dashboard > Service > Deployments
2. Klik deployment yang failed
3. Klik tab **Build Logs**
4. Scroll ke bawah untuk lihat error detail

**Common errors:**

- **"composer: command not found"** → Railway belum install composer
- **"PHP extension missing"** → Tambah extension di nixpacks.toml
- **"Permission denied"** → Tambah chmod di build command
- **"database.sqlite: No such file or directory"** → Tambah mkdir di build command

### Solusi 6: Gunakan Dockerfile (Advanced)

Jika semua solusi di atas gagal, buat Dockerfile:

```dockerfile
FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create directories
RUN mkdir -p database storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache
RUN touch database/database.sqlite
RUN chmod -R 775 storage bootstrap/cache database

# Expose port
EXPOSE 8080

# Start command
CMD php artisan serve --host=0.0.0.0 --port=$PORT
```

Lalu push dan Railway akan detect Dockerfile otomatis.

## Checklist Debugging

- [ ] Push perubahan terbaru ke GitHub
- [ ] Check Build Logs di Railway
- [ ] Pastikan composer.json valid
- [ ] Pastikan PHP version 8.2
- [ ] Set Build Command manual di Railway
- [ ] Set Start Command manual di Railway
- [ ] Check environment variables sudah di-set
- [ ] Coba deploy ulang (Redeploy)

## Masih Gagal?

Jika masih gagal setelah semua solusi di atas:

1. **Screenshot error logs** dari Railway
2. **Copy full error message**
3. Buka issue di GitHub atau tanya di Railway Discord

## Quick Fix Commands

```bash
# Rebuild dengan perubahan terbaru
git add .
git commit -m "Fix build configuration"
git push origin main

# Atau force redeploy via Railway CLI
railway up --detach
```

## Environment Variables yang Wajib

Pastikan ini sudah di-set di Railway:

```env
APP_KEY=base64:...                          # WAJIB! Generate dulu
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite
```

---

**Update:** 2026-05-15

Jika masih ada masalah, hubungi support atau baca dokumentasi Railway: https://docs.railway.app
