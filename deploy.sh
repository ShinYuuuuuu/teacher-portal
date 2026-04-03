#!/bin/bash
# Teacher Portal Deployment Script

echo "🚀 Deploying Teacher Portal to Heroku..."

# Check if we're in the admin directory
if [ ! -f "index.php" ] || [ ! -d ".git" ]; then
    echo "❌ Error: Not in teacher portal directory"
    echo "Please run this script from the admin/ directory"
    exit 1
fi

# Check if Heroku CLI is installed
if ! command -v heroku &> /dev/null; then
    echo "❌ Heroku CLI not found"
    echo "Please install Heroku CLI: https://devcenter.heroku.com/articles/heroku-cli"
    exit 1
fi

echo "🔗 Connecting to Heroku app..."
heroku git:remote -a your-teacher-portal

if [ $? -ne 0 ]; then
    echo "❌ Failed to connect to Heroku app"
    echo "Make sure 'your-teacher-portal' app exists"
    exit 1
fi

echo "📤 Pushing to Heroku..."
git push heroku main

if [ $? -eq 0 ]; then
    echo "✅ Teacher Portal deployed successfully!"
    echo "🌐 URL: https://your-teacher-portal.herokuapp.com"
    echo "🔑 Login: admin / admin123"
else
    echo "❌ Deployment failed"
    echo "Check Heroku logs: heroku logs -a your-teacher-portal"
fi