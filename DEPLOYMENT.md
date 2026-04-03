# Teacher Portal - Deployment Guide

## 🚀 Quick Deploy to Heroku

### Step 1: Create GitHub Repository
1. Go to [GitHub.com](https://github.com)
2. Create new repository: `teacher-portal`
3. Copy the repository URL

### Step 2: Push to GitHub
```bash
# Add GitHub remote
git remote add origin https://github.com/YOUR-USERNAME/teacher-portal.git

# Push to GitHub
git branch -M main
git push -u origin main
```

### Step 3: Create Heroku App
```bash
# Create new Heroku app
heroku create your-teacher-portal-name

# Or create via Heroku Dashboard
# Go to dashboard.heroku.com -> New -> Create new app
```

### Step 4: Deploy
```bash
# Push to Heroku
git push heroku main

# Or use the deploy script
./deploy.sh
```

### Step 5: Update URLs
Edit these files with your actual Heroku app name:
- `README.md`: Change `[YOUR-HEROKU-APP-NAME]` to your app name
- `deploy.sh`: Change `HEROKU_APP_NAME` variable

## 🎯 Access Your Teacher Portal

**URL:** `https://your-app-name.herokuapp.com`

**Login:**
- Username: `admin`
- Password: `admin123`

## 📁 Files Structure
```
teacher-portal/
├── index.php          # Main application
├── Procfile          # Heroku deployment config
├── README.md         # Documentation
├── deploy.sh         # Deployment script
└── .gitignore       # Git ignore rules
```

## 🔧 Troubleshooting

### If deployment fails:
1. Check Heroku logs: `heroku logs -a your-app-name`
2. Verify Procfile is correct: `web: vendor/bin/heroku-php-apache2 .`
3. Ensure PHP buildpack is set: `heroku buildpacks:set heroku/php`

### If login doesn't work:
1. Clear browser cache
2. Check session configuration
3. Verify PHP version compatibility

---
**Ready to deploy!** 🚀