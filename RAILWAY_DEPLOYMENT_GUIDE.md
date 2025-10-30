# GeoSpace Laravel - Railway.app Deployment Guide

Complete guide to deploy your GeoSpace Laravel backend on Railway.app

---

## 📋 Prerequisites

- [x] Railway.app account
- [x] GitHub repository connected to Railway
- [x] Railway CLI installed (optional but recommended)

---

## 🚀 Step-by-Step Deployment

### Step 1: Create New Project on Railway

1. Go to [Railway.app](https://railway.app)
2. Click **"New Project"**
3. Select **"Deploy from GitHub repo"**
4. Choose your `geospace_laravel` repository
5. Select the `chatting-system` branch

---

### Step 2: Add MySQL Database

1. In your Railway project, click **"New"** → **"Database"** → **"Add MySQL"**
2. Railway will automatically create a MySQL database
3. The database credentials will be auto-generated

---

### Step 3: Configure Environment Variables

Click on your Laravel service → **"Variables"** tab → Add these variables:

#### Required Variables

```bash
# Application
APP_NAME="GeoSpace"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

# Generate a new key (run: php artisan key:generate --show)
APP_KEY=base64:YOUR_GENERATED_KEY_HERE

# Database (Railway will provide these)
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

# JWT Authentication (run: php artisan jwt:secret --show)
JWT_SECRET=your_jwt_secret_here
JWT_TTL=60
JWT_REFRESH_TTL=20160

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# CORS (Your React frontend URL)
FRONTEND_URL=https://your-frontend-url.vercel.app
SANCTUM_STATEFUL_DOMAINS=your-frontend-url.vercel.app

# File Storage
FILESYSTEM_DISK=public

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
```

#### How to Get APP_KEY and JWT_SECRET

Run these commands **locally** to generate secrets:

```bash
# Generate APP_KEY
php artisan key:generate --show

# Generate JWT_SECRET
php artisan jwt:secret --show
```

Copy the generated values and add them to Railway environment variables.

---

### Step 4: Configure Railway Service Settings

1. Click on your Laravel service
2. Go to **"Settings"** tab
3. Configure these settings:

#### Build Settings
- **Builder**: Nixpacks (default)
- **Root Directory**: Leave empty (or `/` if needed)
- **Watch Paths**: Leave default

#### Deploy Settings
- **Start Command**: Auto-detected from `nixpacks.toml`
- **Healthcheck Path**: `/api/health` (optional)
- **Region**: Choose closest to your users

---

### Step 5: Link Database to Service

Railway should auto-link the MySQL database. Verify by checking:

1. Go to your Laravel service
2. Click **"Variables"** tab
3. You should see database variables like `${{MySQL.MYSQL_HOST}}`

If not linked:
1. Click **"New Variable"**
2. Click **"Add Reference"**
3. Select your MySQL service
4. Add all database variables

---

### Step 6: Deploy!

1. Click **"Deploy"** button (or push to GitHub)
2. Railway will automatically:
   - Install Composer dependencies
   - Run migrations (`php artisan migrate --force`)
   - Seed essential data (`php artisan db:seed --force`)
   - Start the application

3. Monitor deployment logs for any errors

---

## 🔍 Verify Deployment

### Check if API is Running

```bash
curl https://your-app-name.up.railway.app/api/health
```

### Test Database Connection

```bash
curl https://your-app-name.up.railway.app/api/v1/test
```

### Check Logs

1. Go to Railway dashboard
2. Click on your service
3. Click **"Deployments"** tab
4. View build and runtime logs

---

## 🛠️ Configuration Files

### nixpacks.toml (Already Created)

```toml
[phases.setup]
nixPkgs = ['...', 'mysql80']

[phases.install]
cmds = [
    'composer install --optimize-autoloader --no-dev',
    'php artisan storage:link || true'
]

[phases.build]
cmds = [
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache'
]

[start]
cmd = 'php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}'
```

This file tells Railway how to:
- ✅ Install MySQL client
- ✅ Install PHP dependencies
- ✅ Cache configurations for performance
- ✅ Run migrations and seeders
- ✅ Start the server

---

## 🔐 Security Checklist

Before going live, ensure:

- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] Unique `JWT_SECRET` set
- [ ] `APP_ENV=production`
- [ ] Database credentials secured
- [ ] CORS configured correctly
- [ ] `.env` file not committed to Git (already in `.gitignore`)

---

## 🌐 Connect Frontend

Update your React frontend `.env`:

```bash
VITE_API_URL=https://your-app-name.up.railway.app/api
VITE_WS_URL=wss://your-app-name.up.railway.app
```

Update CORS in Railway environment variables:

```bash
FRONTEND_URL=https://your-frontend.vercel.app
SANCTUM_STATEFUL_DOMAINS=your-frontend.vercel.app
```

---

## 🐛 Troubleshooting

### Issue: "500 Internal Server Error"

**Solution**: Check logs and ensure:
```bash
APP_KEY is set correctly
Database connection is working
Migrations ran successfully
```

### Issue: "CORS Error"

**Solution**: Update environment variables:
```bash
FRONTEND_URL=https://your-exact-frontend-url.com
```

Also verify `config/cors.php` allows your frontend.

### Issue: "Database Connection Failed"

**Solution**: Verify database variables:
```bash
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}
```

Ensure MySQL service is running in Railway.

### Issue: "Route Not Found"

**Solution**: Clear caches:
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

Or trigger a redeploy in Railway.

### Issue: "Migrations Not Running"

**Solution**: Check if `nixpacks.toml` start command includes:
```bash
php artisan migrate --force
```

Or run manually from Railway shell.

---

## 📊 Monitoring

### View Application Logs

```bash
# Install Railway CLI
npm install -g @railway/cli

# Login
railway login

# Link to your project
railway link

# View logs
railway logs
```

### Database Management

Access database directly:

1. Go to MySQL service in Railway
2. Click **"Data"** tab
3. Or use **"Connect"** for connection string
4. Use MySQL client to connect

---

## 🚀 Performance Optimization

### Enable OPcache (Recommended)

Add to environment variables:
```bash
PHP_OPCACHE_ENABLE=1
PHP_OPCACHE_REVALIDATE_FREQ=0
PHP_MEMORY_LIMIT=512M
```

### Queue Workers (Optional)

For background jobs:

1. Add new service in Railway
2. Same codebase, different start command:
```bash
php artisan queue:work --tries=3
```

3. Set environment variables same as main service

---

## 🔄 Continuous Deployment

Railway auto-deploys when you push to GitHub:

```bash
git add .
git commit -m "your changes"
git push origin chatting-system
```

Railway will automatically:
1. Detect changes
2. Build new deployment
3. Run migrations
4. Deploy new version
5. Zero-downtime deployment

---

## 📝 Environment Variables Summary

Here's the complete list you need:

```env
# Application
APP_NAME=GeoSpace
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE
APP_URL=https://your-app.up.railway.app

# Database
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

# JWT
JWT_SECRET=your_jwt_secret
JWT_TTL=60
JWT_REFRESH_TTL=20160

# CORS
FRONTEND_URL=https://your-frontend.vercel.app
SANCTUM_STATEFUL_DOMAINS=your-frontend.vercel.app

# Session
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
```

---

## ✅ Deployment Checklist

- [ ] Railway project created
- [ ] MySQL database added
- [ ] All environment variables set
- [ ] Database linked to service
- [ ] `nixpacks.toml` committed to repo
- [ ] GitHub repo connected
- [ ] Branch selected (chatting-system)
- [ ] First deployment successful
- [ ] API endpoints accessible
- [ ] Database migrations ran
- [ ] Seed data inserted
- [ ] Frontend connected
- [ ] CORS working
- [ ] Test all API endpoints

---

## 🎉 You're Live!

Your Laravel backend should now be live at:
```
https://your-app-name.up.railway.app
```

Test your API:
```bash
curl https://your-app-name.up.railway.app/api/v1/health
```

---

## 📚 Additional Resources

- [Railway Documentation](https://docs.railway.app/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Nixpacks Laravel](https://nixpacks.com/docs/providers/php)
- [Railway Discord](https://discord.gg/railway) - Get help

---

## 🆘 Need Help?

If you encounter issues:

1. Check Railway deployment logs
2. Verify all environment variables
3. Ensure database is running
4. Check Laravel logs: `storage/logs/laravel.log`
5. Test API endpoints individually

**Common First Deployment Issues**:
- Missing APP_KEY
- Wrong database credentials
- CORS configuration
- Route caching conflicts

---

**Deployed successfully?** 🎉

Next steps:
1. Connect your React frontend
2. Test all features
3. Monitor performance
4. Set up domain (optional)
5. Enable SSL (automatic on Railway)
