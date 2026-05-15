#!/bin/bash

echo "🚀 Starting Railway deployment setup..."

# Check if git is initialized
if [ ! -d .git ]; then
    echo "📦 Initializing git repository..."
    git init
    git add .
    git commit -m "Initial commit for Railway deployment"
    echo "✅ Git repository initialized"
else
    echo "✅ Git repository already exists"
fi

# Check if Railway CLI is installed
if ! command -v railway &> /dev/null; then
    echo "⚠️  Railway CLI not found. Installing..."
    npm install -g @railway/cli
    echo "✅ Railway CLI installed"
else
    echo "✅ Railway CLI already installed"
fi

echo ""
echo "📋 Next steps:"
echo "1. Push your code to GitHub:"
echo "   git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO.git"
echo "   git branch -M main"
echo "   git push -u origin main"
echo ""
echo "2. Login to Railway:"
echo "   railway login"
echo ""
echo "3. Create new project on Railway dashboard:"
echo "   https://railway.app/new"
echo ""
echo "4. Follow the complete guide in DEPLOY_RAILWAY.md"
echo ""
echo "✨ Setup complete! Read DEPLOY_RAILWAY.md for detailed instructions."
