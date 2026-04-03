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

# Set your Heroku app name here (change this to your actual Heroku app name)
HEROKU_APP_NAME="your-teacher-portal"

echo "🔗 Connecting to Heroku app..."
heroku git:remote -a $HEROKU_APP_NAME

if [ $? -ne 0 ]; then
    echo "❌ Failed to connect to Heroku app"
    echo "Make sure '$HEROKU_APP_NAME' app exists on Heroku"
    echo "Create it with: heroku create $HEROKU_APP_NAME"
    exit 1
fi

echo "📤 Pushing to Heroku..."
git push heroku main

if [ $? -eq 0 ]; then
    echo "✅ Teacher Portal deployed successfully!"
    echo "🌐 URL: https://$HEROKU_APP_NAME.herokuapp.com"
    echo "🔑 Login: admin / admin123"
else
    echo "❌ Deployment failed"
    echo "Check Heroku logs: heroku logs -a $HEROKU_APP_NAME"
fi