# 🌍 GeoSpace Laravel API

A comprehensive Laravel-based API platform for geospatial services, featuring JWT authentication, role-based access control, and modern development tools.

## 🚀 Quick Start

```bash
# Install dependencies
composer install && npm install

# Setup environment
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# Setup database
php artisan migrate --seed

# Start development server
composer run dev
```

## 🛠️ Prerequisites

- **PHP 8.2+** with required extensions
- **Composer** and **Node.js 18+**
- **SQLite** (default) or **MySQL/PostgreSQL**

## 🏃‍♂️ Running the Project

### Development Mode (All Services)
```bash
composer run dev
```
This starts Laravel server, queue worker, log monitoring, and Vite dev server.

### Individual Services
```bash
php artisan serve          # Laravel server (http://localhost:8000)
php artisan queue:work     # Queue worker
php artisan pail           # Real-time logs
npm run dev               # Frontend assets
```

## 🧪 API Testing

**Base URL**: `http://localhost:8000/api/v1`

### Test Login
```bash
curl -X POST "http://localhost:8000/api/v1/Login" \
  -H "Content-Type: application/json" \
  -d '{"Email": "test@example.com", "Password": "test123"}'
```

### Test Protected Endpoint
```bash
curl -X GET "http://localhost:8000/api/v1/me" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

## 🐛 Debugging API Calls

### 1. Real-time Log Monitoring
```bash
php artisan pail                    # All logs
php artisan pail --filter="error"   # Error logs only
php artisan pail --filter="api"     # API logs only
```

### 2. Enable Debug Mode
```env
# In .env file
APP_DEBUG=true
LOG_LEVEL=debug
```

### 3. Common Debug Commands
```bash
php artisan optimize:clear    # Clear all caches
php artisan route:list        # List all routes
php artisan config:show       # Show configuration
tail -f storage/logs/laravel.log  # Monitor log file
```

### 4. Database Debugging
```bash
php artisan migrate:status    # Check migrations
php artisan tinker           # Interactive shell
>>> DB::connection()->getPdo();  # Test DB connection
```

### 5. JWT Token Issues
```bash
php artisan jwt:secret --force    # Regenerate JWT secret
php artisan config:show jwt       # Check JWT config
```

### 6. Debugging Tools & Techniques

#### API Request/Response Logging
Add to your controllers:
```php
Log::info('API Request', [
    'endpoint' => request()->path(),
    'method' => request()->method(),
    'data' => request()->all()
]);
```

#### Database Query Logging
```bash
php artisan tinker
>>> DB::enableQueryLog();
>>> // Make API calls
>>> DB::getQueryLog();
```

#### Common Issues & Solutions
- **CORS Issues**: Check `config/cors.php`
- **Token Expired**: Login again or refresh token
- **Database Locked**: Check SQLite file permissions
- **Route Not Found**: Run `php artisan route:cache`

## 📊 Key Features

- **JWT Authentication** with role-based access
- **Real-time logging** with Laravel Pail
- **Modern frontend** with Vite + TailwindCSS
- **Comprehensive API** with 22+ endpoints
- **SQLite database** for easy development
- **Queue system** for background jobs

## 📁 Project Structure

```
app/
├── Http/Controllers/     # API controllers
├── Models/              # Eloquent models (User, Project, etc.)
├── Helpers/             # Helper classes
└── Providers/           # Service providers

routes/api.php           # API route definitions
config/                  # Configuration files
database/migrations/     # Database structure
```

## 🔐 Authentication

- **JWT tokens** (60min expiry, 2-week refresh)
- **Roles**: Admin, Freelancer, Company, Support, Visitor
- **Header**: `Authorization: Bearer <token>`

## 📋 Available API Endpoints

### Public Endpoints (No Auth Required)
- `POST /Login` - User authentication
- `POST /SignUpFreelancer` - Freelancer registration
- `POST /SignUpCompanyDetails` - Company registration
- `POST /LogVisitor` - Visitor logging

### Protected Endpoints (JWT Required)
- `GET /me` - Current user profile
- `POST /logout` - Logout user
- `GET /GetMenusByRoleId` - Get menus by role
- `GET /Notifications` - Get notifications
- `GET /company/*` - Company-specific endpoints
- `GET /admin/*` - Admin-specific endpoints

## ❓ Troubleshooting

### Common Issues
```bash
# Missing application key
php artisan key:generate

# Missing JWT secret
php artisan jwt:secret

# Database issues
php artisan migrate
chmod -R 755 storage/ bootstrap/cache/

# Clear caches
php artisan optimize:clear
```

### Environment Setup Issues
1. Copy `.env.example` to `.env`
2. Generate app key: `php artisan key:generate`
3. Generate JWT secret: `php artisan jwt:secret`
4. Set up database connection in `.env`
5. Run migrations: `php artisan migrate`

### Getting Help
1. Check logs: `storage/logs/laravel.log`
2. Use real-time monitoring: `php artisan pail`
3. Review API documentation: `API_DOCUMENTATION.md`

## 📖 API Documentation

See `API_DOCUMENTATION.md` for complete endpoint documentation with examples.

**Test Credentials:**
- Email: `test@example.com`
- Password: `test123`
- Role: Admin

---

**Happy Coding! 🚀**