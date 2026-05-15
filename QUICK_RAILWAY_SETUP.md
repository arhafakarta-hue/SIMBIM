# Quick Railway Setup (5 Minutes)

## Prerequisites
- GitHub account
- Railway account (sign up at railway.app)
- This repo pushed to GitHub

---

## Step 1: Push to GitHub (if not done)

```bash
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

---

## Step 2: Create Railway Project

1. Go to https://railway.app
2. Click **"Start a New Project"**
3. Click **"Deploy from GitHub repo"**
4. Select your repository: `simbim-realtime-laravel`
5. Click **"Deploy Now"**

Railway will start building automatically.

---

## Step 3: Add Volume (CRITICAL!)

**Without this, your database will be deleted on every deploy!**

1. Click on your service
2. Go to **Settings** tab
3. Scroll to **Volumes** section
4. Click **"+ New Volume"**
5. Enter mount path: `/data`
6. Click **"Add"**

---

## Step 4: Set Environment Variables

1. Click **Variables** tab
2. Click **"+ New Variable"** and add these one by one:

### Required Variables:

```
APP_NAME=SIMBIM Realtime
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
DB_CONNECTION=sqlite
DB_DATABASE=/data/database.sqlite
SESSION_DRIVER=file
CACHE_STORE=file
LOG_CHANNEL=stack
LOG_LEVEL=info
```

### Generate APP_KEY:

**Option A:** Run locally and copy the output:
```bash
php artisan key:generate --show
```

**Option B:** Use online generator:
- Go to: https://generate-random.org/laravel-key-generator
- Copy the generated key

Add to Railway:
```
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
```

---

## Step 5: Get Your Railway Domain

1. Go to **Settings** tab
2. Scroll to **Domains** section
3. Copy your domain (e.g., `simbim-production-abc123.up.railway.app`)

---

## Step 6: Set APP_URL

1. Go back to **Variables** tab
2. Add/Update:

```
APP_URL=https://YOUR_RAILWAY_DOMAIN_HERE
```

**Example:**
```
APP_URL=https://simbim-production-abc123.up.railway.app
```

⚠️ **IMPORTANT:** Use `https://` (not `http://`)

---

## Step 7: Redeploy

1. Click **Deployments** tab
2. Click **"Deploy"** button (top right)
3. Wait for build to complete (2-3 minutes)

---

## Step 8: Test Your App

1. Click on your domain link
2. You should see the login page **with CSS styling**
3. Try logging in:
   - Email: `admin@simbim.test`
   - Password: `password`

---

## ✅ Success Checklist

- [ ] Volume mounted at `/data`
- [ ] `APP_KEY` is set
- [ ] `APP_URL` is set with `https://` and your Railway domain
- [ ] `DB_DATABASE=/data/database.sqlite`
- [ ] Build completed successfully
- [ ] App loads with CSS styling
- [ ] Can login with demo account

---

## ❌ Troubleshooting

### CSS Not Loading?

1. Check `APP_URL` is set correctly with `https://`
2. Press `F12` in browser → Network tab → Check `style.css` status
3. If 404: Wrong `APP_URL`
4. Clear browser cache: `Ctrl+Shift+R`

### Database Errors?

1. Check Volume is mounted at `/data`
2. Check `DB_DATABASE=/data/database.sqlite`
3. Check Deploy Logs for migration errors
4. Redeploy

### Build Failed?

1. Check Build Logs for specific error
2. Ensure `composer.json` is valid
3. Ensure all files are committed to GitHub
4. Try redeploying

### App Crashes?

1. Check Deploy Logs
2. Ensure `APP_KEY` is set
3. Ensure all required variables are set
4. Check View Logs for runtime errors

---

## Need More Help?

Read the complete guide: [RAILWAY_COMPLETE_FIX.md](RAILWAY_COMPLETE_FIX.md)

---

**Estimated Time:** 5-10 minutes  
**Cost:** $5-8/month  
**Last Updated:** 2026-05-15
