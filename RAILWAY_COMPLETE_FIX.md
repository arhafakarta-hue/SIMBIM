# Railway Deployment - Complete Fix Guide

## Your Problems:
1. ❌ Database not working
2. ❌ CSS not appearing

## Root Causes:
1. Database path incorrect or volume not mounted
2. APP_URL not set correctly
3. Missing environment variables

---

## STEP-BY-STEP FIX (Follow Exactly)

### Step 1: Update Railway Configuration Files

The configuration files have been updated. Make sure to commit and push:

```bash
git add .
git commit -m "Fix Railway deployment - database and CSS issues"
git push origin main
```

### Step 2: Railway Dashboard Setup

#### A. Create/Configure Web Service

1. Go to **Railway Dashboard** → Your Project
2. If you don't have a service yet, click **"+ New"** → **"GitHub Repo"** → Select this repo
3. Click on your **Web Service**

#### B. Set Environment Variables (CRITICAL!)

Click **Variables** tab and add these **EXACTLY**:

```env
APP_NAME=SIMBIM Realtime
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id
APP_FALLBACK_LOCALE=en

# IMPORTANT: Generate this first! (see Step 3)
APP_KEY=

# IMPORTANT: Replace with YOUR Railway domain (see Step 4)
APP_URL=https://your-service-name.up.railway.app

# Database - Use Railway volume path
DB_CONNECTION=sqlite
DB_DATABASE=/data/database.sqlite

# Session & Cache
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=info

# Broadcasting (if using Reverb)
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=simbim-app
REVERB_APP_KEY=simbim-key
REVERB_APP_SECRET=simbim-secret
REVERB_HOST=your-service-name.up.railway.app
REVERB_PORT=443
REVERB_SCHEME=https
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080
```

### Step 3: Generate APP_KEY

**Option A: Using Railway CLI (Recommended)**

If you have Railway CLI installed:

```bash
railway run php artisan key:generate --show
```

Copy the output (starts with `base64:...`) and paste it to `APP_KEY` variable in Railway.

**Option B: Using Local Environment**

Run locally:

```bash
php artisan key:generate --show
```

Copy the output and paste it to `APP_KEY` variable in Railway.

**Option C: Manual Generation**

Go to: https://generate-random.org/laravel-key-generator

Copy the generated key and paste it to `APP_KEY` variable in Railway.

### Step 4: Set Correct APP_URL

1. In Railway Dashboard → Your Service → **Settings** tab
2. Scroll to **Domains** section
3. Copy your Railway domain (e.g., `simbim-production-abc123.up.railway.app`)
4. Go back to **Variables** tab
5. Update `APP_URL` to:
   ```
   APP_URL=https://simbim-production-abc123.up.railway.app
   ```
   ⚠️ Use `https://` (not `http://`) and your ACTUAL domain

6. Also update `REVERB_HOST` with the same domain (without `https://`):
   ```
   REVERB_HOST=simbim-production-abc123.up.railway.app
   ```

### Step 5: Add Persistent Volume for Database

**CRITICAL: Without this, your database will be deleted on every deploy!**

1. Railway Dashboard → Your Service → **Settings** tab
2. Scroll to **Volumes** section
3. Click **"+ New Volume"**
4. Set:
   - **Mount Path**: `/data`
   - Click **Add**

This ensures your SQLite database persists across deployments.

### Step 6: Configure Build & Deploy Settings

1. Railway Dashboard → Your Service → **Settings** tab
2. Scroll to **Build** section:
   - **Builder**: NIXPACKS (should be auto-detected)
   - **Build Command**: Leave empty (uses nixpacks.toml)
   
3. Scroll to **Deploy** section:
   - **Start Command**: Leave empty (uses railway.json)
   - **Healthcheck Path**: `/up`
   - **Healthcheck Timeout**: 300

### Step 7: Deploy!

1. Click **Deploy** button (top right)
2. Or push to GitHub (auto-deploys)

### Step 8: Verify Deployment

#### Check Build Logs:
1. Railway Dashboard → **Deployments** tab
2. Click latest deployment
3. Check **Build Logs** - should show:
   - ✅ Composer install successful
   - ✅ Database directory created
   - ✅ Migrations ran

#### Check Deploy Logs:
1. Click **Deploy Logs** tab
2. Should show:
   - ✅ Server started on port 8080
   - ✅ No errors

#### Check Application:
1. Click **View Logs** tab (live logs)
2. Open your Railway domain in browser
3. Check:
   - ✅ CSS loads (page looks styled)
   - ✅ Can login with demo accounts
   - ✅ No database errors

### Step 9: Test CSS Loading

1. Open your Railway URL in browser
2. Press `F12` to open Developer Tools
3. Go to **Network** tab
4. Refresh page (`Ctrl+R`)
5. Filter by "CSS"
6. Check `style.css`:
   - ✅ Status: **200 OK** (CSS loaded)
   - ❌ Status: **404** (APP_URL wrong - go back to Step 4)

### Step 10: Test Database

1. Try logging in with demo account:
   - Email: `admin@simbim.test`
   - Password: `password`

2. If login fails with database error:
   - Check Volume is mounted at `/data` (Step 5)
   - Check `DB_DATABASE=/data/database.sqlite` in Variables (Step 2)
   - Check Deploy Logs for migration errors

---

## Common Issues & Solutions

### Issue 1: "No application encryption key has been specified"
**Solution:** Generate and set `APP_KEY` (Step 3)

### Issue 2: CSS not loading (404 error)
**Solution:** Set correct `APP_URL` with `https://` and your Railway domain (Step 4)

### Issue 3: Database errors / "database locked"
**Solution:** 
- Add Volume at `/data` (Step 5)
- Set `DB_DATABASE=/data/database.sqlite` (Step 2)
- Redeploy

### Issue 4: "SQLSTATE[HY000]: General error: 14 unable to open database file"
**Solution:**
- Volume not mounted correctly
- Check Volume mount path is `/data`
- Check railway.json creates `/data` directory
- Redeploy

### Issue 5: Build fails
**Solution:**
- Check Build Logs for specific error
- Ensure `composer.json` is valid
- Ensure PHP 8.2 is available
- Try manual build command in Settings

### Issue 6: App crashes after deploy
**Solution:**
- Check Deploy Logs for errors
- Ensure all environment variables are set
- Check `APP_KEY` is set
- Check database path is correct

---

## Quick Checklist

Before asking for help, verify:

- [ ] `APP_KEY` is generated and set
- [ ] `APP_URL` is set with `https://` and correct Railway domain
- [ ] Volume is added and mounted at `/data`
- [ ] `DB_DATABASE=/data/database.sqlite` is set
- [ ] All environment variables from Step 2 are set
- [ ] Latest code is pushed to GitHub
- [ ] Build logs show no errors
- [ ] Deploy logs show server started
- [ ] Can access Railway URL in browser

---

## Still Not Working?

### Get Help:

1. **Check Railway Logs:**
   - Build Logs (for build errors)
   - Deploy Logs (for startup errors)
   - View Logs (for runtime errors)

2. **Screenshot and share:**
   - Build Logs (if build fails)
   - Deploy Logs (if deploy fails)
   - Browser Console (F12) for CSS/JS errors
   - Environment Variables list (hide APP_KEY!)

3. **Common commands to run via Railway:**

   ```bash
   # Check database file exists
   ls -la /data/
   
   # Check public directory
   ls -la /app/public/
   
   # Clear config cache
   php artisan config:clear
   
   # Run migrations manually
   php artisan migrate --force
   ```

---

## Alternative: Use Fly.io Instead

If Railway keeps failing, Fly.io is a good alternative:

**Pros:**
- More reliable for SQLite apps
- Better documentation
- Free tier available
- Persistent volumes work better

**Setup time:** 20-30 minutes

Let me know if you want a Fly.io deployment guide instead!

---

**Last Updated:** 2026-05-15
**Status:** Complete fix for database + CSS issues
