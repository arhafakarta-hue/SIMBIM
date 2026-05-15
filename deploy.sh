#!/bin/bash

# Railway Deployment Script for SIMBIM
# This script helps automate the deployment process

set -e

echo "🚀 SIMBIM Railway Deployment Helper"
echo "===================================="
echo ""

# Check if git is initialized
if [ ! -d .git ]; then
    echo "❌ Git repository not initialized!"
    echo "Run: git init"
    exit 1
fi

# Check for uncommitted changes
if [[ -n $(git status -s) ]]; then
    echo "⚠️  You have uncommitted changes!"
    echo ""
    read -p "Do you want to commit all changes? (y/n) " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        git add .
        read -p "Enter commit message: " commit_msg
        git commit -m "$commit_msg"
        echo "✅ Changes committed"
    else
        echo "⚠️  Please commit your changes before deploying"
        exit 1
    fi
fi

# Check if remote exists
if ! git remote | grep -q 'origin'; then
    echo ""
    echo "📦 No git remote found. Please add your GitHub repository:"
    read -p "Enter GitHub repository URL: " repo_url
    git remote add origin "$repo_url"
    echo "✅ Remote added"
fi

# Push to GitHub
echo ""
echo "📤 Pushing to GitHub..."
git push -u origin main || git push origin main
echo "✅ Code pushed to GitHub"

# Check if Railway CLI is installed
echo ""
if ! command -v railway &> /dev/null; then
    echo "⚠️  Railway CLI not installed"
    read -p "Install Railway CLI now? (y/n) " -n 1 -r
    echo ""
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        npm install -g @railway/cli
        echo "✅ Railway CLI installed"
    else
        echo "ℹ️  Install manually: npm i -g @railway/cli"
        exit 0
    fi
fi

# Railway login
echo ""
echo "🔐 Logging into Railway..."
railway login

# Link or create project
echo ""
echo "🔗 Linking Railway project..."
if railway status &> /dev/null; then
    echo "✅ Already linked to Railway project"
else
    echo "Creating new Railway project..."
    railway init
fi

echo ""
echo "✅ Deployment preparation complete!"
echo ""
echo "📋 Next steps:"
echo "1. Go to Railway dashboard: https://railway.app/dashboard"
echo "2. Create a second service for Reverb (WebSocket)"
echo "3. Set environment variables in both services"
echo "4. Generate APP_KEY: railway run php artisan key:generate --show"
echo "5. Run migrations: railway run php artisan migrate --seed --force"
echo ""
echo "📖 Read DEPLOY_RAILWAY.md for detailed instructions"
echo ""
