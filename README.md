# 🌍 GeoSpace Laravel Backend API

A comprehensive Laravel-based REST API platform for geospatial services and freelance management, featuring JWT authentication, role-based access control, timesheet management, and modern development tools.

## 📋 Table of Contents

- [Quick Start](#-quick-start)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Test Credentials](#-test-credentials)
- [Running the Project](#-running-the-project)
- [API Testing](#-api-testing)
- [Debugging](#-debugging-api-calls)
- [Key Features](#-key-features)
- [Project Structure](#-project-structure)
- [Authentication](#-authentication)
- [Available Endpoints](#-available-api-endpoints)
- [Database](#-database)
- [Troubleshooting](#-troubleshooting)

---

## 🚀 Quick Start

Get up and running in minutes:

```bash
# Clone and navigate to project
cd geospace_laravel

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

**Access the API at:** `http://localhost:8000/api/v1`

---

## 🔑 Test Credentials

Use these credentials to test the API immediately after setup:

### Default Test Users

| Role | Email | Password | Role ID | Description |
|------|-------|----------|---------|-------------|
| **Admin** | `admin@geospace.com` | `password123` | 1 | Full system access |
| **Freelancer** | `freelancer@geospace.com` | `password123` | 2 | Contractor/service provider |
| **Company** | `company@geospace.com` | `password123` | 3 | Hiring organization |
| **Support** | `support@geospace.com` | `password123` | 4 | Customer support agent |

### Quick Login Example

```bash
curl -X POST "http://localhost:8000/api/v1/Login" \
  -H "Content-Type: application/json" \
  -d '{
    "Email": "admin@geospace.com",
    "Password": "password123"
  }'
```

**Response:**
```json
{
  "Token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "UserDetails": {
    "UserId": 1,
    "UserName": "admin@geospace.com",
    "Email": "admin@geospace.com",
    "RoleId": 1,
    "RoleName": "Admin"
  }
}
```

---

## 🛠️ Prerequisites

Ensure you have the following installed:

- **PHP 8.2+** with extensions:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
- **Composer 2.x+**
- **Node.js 18+ & NPM**
- **SQLite** (default) or **MySQL/PostgreSQL**

### Check Your Environment

```bash
php -v                    # Should be 8.2 or higher
composer -v               # Should be 2.x
node -v                   # Should be 18.x or higher
npm -v                    # Should be 9.x or higher
```

---

## 📦 Installation

### Step 1: Clone Repository (if not already done)

```bash
git clone <repository-url>
cd geospace_laravel
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### Step 4: Database Setup

The project uses SQLite by default. For MySQL/PostgreSQL, update `.env`:

**SQLite (Default):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

**MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=geospace_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**Run Migrations and Seeders:**

```bash
# Create database tables and seed with test data
php artisan migrate --seed

# Or run separately
php artisan migrate
php artisan db:seed
```

### Step 5: File Permissions

```bash
# Linux/Mac
chmod -R 755 storage bootstrap/cache

# Or if needed
sudo chown -R www-data:www-data storage bootstrap/cache
```

---

## 🏃‍♂️ Running the Project

### Development Mode (Recommended)

Start all services with one command:

```bash
composer run dev
```

This runs:
- Laravel server (`http://localhost:8000`)
- Queue worker (background jobs)
- Laravel Pail (real-time logs)
- Vite dev server (frontend assets)

### Individual Services

Run services separately if needed:

```bash
# Laravel API server
php artisan serve
# Access at: http://localhost:8000

# Queue worker (for background jobs)
php artisan queue:work

# Real-time log monitoring
php artisan pail

# Frontend asset compilation
npm run dev

# Build assets for production
npm run build
```

### Production Mode

```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# Run with queue worker
php artisan queue:work --daemon
```

---

## 🧪 API Testing

### Base URL
```
http://localhost:8000/api/v1
```

### 1. Test Authentication

**Login Request:**
```bash
curl -X POST "http://localhost:8000/api/v1/Login" \
  -H "Content-Type: application/json" \
  -d '{
    "Email": "admin@geospace.com",
    "Password": "password123"
  }'
```

**Save the Token from Response:**
```json
{
  "Token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "UserDetails": { ... }
}
```

### 2. Test Protected Endpoints

**Get Current User Profile:**
```bash
curl -X GET "http://localhost:8000/api/v1/me" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Get Notifications:**
```bash
curl -X GET "http://localhost:8000/api/v1/Notifications" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Get Dropdown Data:**
```bash
curl -X GET "http://localhost:8000/api/v1/DropdownDataByCategory?Category=Skills" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### 3. Using Postman

1. **Import Environment:**
   - Base URL: `http://localhost:8000/api/v1`
   - Set variable: `token` with your JWT token

2. **Set Headers for Protected Routes:**
   - `Authorization: Bearer {{token}}`
   - `Content-Type: application/json`
   - `Accept: application/json`

3. **Test Login → Save Token → Test Protected Routes**

---

## 🐛 Debugging API Calls

### 1. Real-time Log Monitoring

Laravel Pail provides beautiful real-time log monitoring:

```bash
# Monitor all logs
php artisan pail

# Filter by level
php artisan pail --filter="error"
php artisan pail --filter="info"

# Filter by content
php artisan pail --filter="api"
php artisan pail --filter="database"
```

### 2. Enable Debug Mode

Edit `.env` file:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

**⚠️ Warning:** Never enable `APP_DEBUG=true` in production!

### 3. Common Debug Commands

```bash
# Clear all caches
php artisan optimize:clear

# List all routes
php artisan route:list

# List all API routes only
php artisan route:list --path=api

# Show current configuration
php artisan config:show

# Monitor log file directly
tail -f storage/logs/laravel.log

# Clear specific caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 4. Database Debugging

```bash
# Check migration status
php artisan migrate:status

# Rollback migrations
php artisan migrate:rollback

# Fresh migration with seed
php artisan migrate:fresh --seed

# Interactive shell
php artisan tinker
```

**Tinker Examples:**
```php
# Test database connection
>>> DB::connection()->getPdo();

# Check users
>>> App\Models\User::count();

# Find user by email
>>> App\Models\User::where('email', 'admin@geospace.com')->first();

# Enable query logging
>>> DB::enableQueryLog();
>>> App\Models\User::all();
>>> DB::getQueryLog();
```

### 5. JWT Token Debugging

```bash
# Regenerate JWT secret
php artisan jwt:secret --force

# Check JWT configuration
php artisan config:show jwt

# Test token in tinker
php artisan tinker
>>> $user = App\Models\User::first();
>>> $token = auth()->login($user);
>>> echo $token;
```

### 6. API Request/Response Logging

Add to your `.env` for detailed API logging:

```env
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

Check logs at: `storage/logs/laravel.log`

### 7. Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| **CORS Errors** | Check `config/cors.php` configuration |
| **Token Expired** | Login again or implement token refresh |
| **Database Locked** | Check SQLite file permissions: `chmod 644 database/database.sqlite` |
| **Route Not Found** | Run `php artisan route:cache` |
| **500 Internal Error** | Check `storage/logs/laravel.log` |
| **Unauthorized 401** | Verify token is valid and not expired |

---

## 📊 Key Features

- ✅ **JWT Authentication** - Secure token-based authentication
- ✅ **Role-Based Access Control** - 5 different user roles
- ✅ **Real-time Logging** - Laravel Pail for beautiful logs
- ✅ **Modern Frontend** - Vite + TailwindCSS integration
- ✅ **Comprehensive API** - 25+ RESTful endpoints
- ✅ **SQLite Database** - Easy development setup
- ✅ **Queue System** - Background job processing
- ✅ **Timesheet Management** - Track work hours and approvals
- ✅ **Notification System** - Real-time user notifications
- ✅ **Activity Logging** - Audit trail for all actions
- ✅ **Payment Tracking** - Integrated payment management

---

## 📁 Project Structure

```
geospace_laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # API Controllers
│   │   │   ├── CommonController.php
│   │   │   ├── CompanyController.php
│   │   │   ├── AdminController.php
│   │   │   └── TimesheetController.php
│   │   ├── Middleware/         # Custom middleware
│   │   └── Requests/           # Form requests
│   ├── Models/                 # Eloquent models
│   │   ├── User.php
│   │   ├── Project.php
│   │   ├── Timesheet.php
│   │   └── ...
│   ├── Helpers/                # Helper classes
│   │   ├── MessageHelper.php
│   │   └── AesEncryptionHelper.php
│   └── Providers/              # Service providers
│
├── bootstrap/
│   └── cache/                  # Bootstrap cache
│
├── config/                     # Configuration files
│   ├── auth.php
│   ├── database.php
│   ├── jwt.php
│   └── cors.php
│
├── database/
│   ├── migrations/             # Database migrations
│   ├── seeders/                # Database seeders
│   └── database.sqlite         # SQLite database file
│
├── public/                     # Public assets
│   └── index.php               # Entry point
│
├── resources/
│   ├── views/                  # Blade templates
│   ├── js/                     # JavaScript files
│   └── css/                    # CSS files
│
├── routes/
│   ├── api.php                 # API routes
│   ├── web.php                 # Web routes
│   └── channels.php            # Broadcast channels
│
├── storage/
│   ├── app/                    # Application files
│   ├── framework/              # Framework files
│   └── logs/                   # Log files
│
├── tests/                      # PHPUnit tests
│
├── .env.example                # Environment template
├── composer.json               # PHP dependencies
├── package.json                # Node dependencies
├── README.md                   # This file
└── API_DOCUMENTATION.md        # API documentation
```

---

## 🔐 Authentication

### JWT Token System

- **Token Type:** JSON Web Token (JWT)
- **Expiry:** 60 minutes (configurable)
- **Refresh Token:** 2 weeks
- **Algorithm:** HS256

### Authentication Flow

1. **Login** → Receive JWT token
2. **Include Token** in Authorization header for protected routes
3. **Token Expires** → Login again or refresh token
4. **Logout** → Invalidates current token

### Using Authentication

**Header Format:**
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

**Protected Route Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/me" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### User Roles

| Role ID | Role Name | Description |
|---------|-----------|-------------|
| 1 | Admin | System administrator - full access |
| 2 | Freelancer | Independent contractor providing services |
| 3 | Company | Organization hiring freelancers |
| 4 | Support | Customer support agent |
| 5 | Visitor | Unauthenticated user |

---

## 📋 Available API Endpoints

### Public Endpoints (No Authentication)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/Login` | User authentication |
| POST | `/SignUpFreelancer` | Register new freelancer |
| POST | `/SignUpFreelancerDetails` | Add freelancer details |
| POST | `/SignUpCompanyDetails` | Add company information |
| POST | `/LogVisitor` | Log visitor activity |
| GET | `/api/auth/linkedin/callback/signup` | LinkedIn OAuth |

### Protected Endpoints (Requires JWT)

#### Common Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/me` | Get current user profile |
| POST | `/logout` | Logout and invalidate token |
| GET | `/GetMenusByRoleId?RoleId={id}` | Get menu items by role |
| GET | `/Notifications` | Get user notifications |
| POST | `/UpdateNotification` | Mark notification as read |
| GET | `/DropdownDataByCategory?Category={name}` | Get dropdown options |

#### Company Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/company/CurrentProjectList` | List active projects |
| GET | `/company/ActiveFreelancerList` | List active freelancers |
| GET | `/company/CompanyPendingTimesheetList` | Pending timesheets |
| GET | `/company/NotificationList` | Company notifications |
| GET | `/company/DashboardStats` | Dashboard statistics |

#### Admin Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/VerifiedUserList` | List verified users |
| GET | `/admin/PendingVerificationList` | Users pending verification |
| GET | `/admin/SuspendedAccountsList` | Suspended accounts |
| GET | `/admin/GetUserDetails?UserId={id}` | Get user details |
| POST | `/admin/UpdateUserStatus` | Activate/deactivate user |
| POST | `/admin/VerifyUser` | Verify user account |

#### Timesheet Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/timesheets` | List all timesheets |
| GET | `/admin/timesheets/{id}` | Get timesheet details |
| POST | `/admin/timesheets` | Create new timesheet |
| PUT | `/admin/timesheets/{id}` | Update timesheet |
| DELETE | `/admin/timesheets/{id}` | Delete timesheet |
| POST | `/admin/timesheets/{id}/approve` | Approve timesheet |
| POST | `/admin/timesheets/{id}/reject` | Reject timesheet |
| GET | `/admin/timesheets/pending` | Get pending timesheets |

**See `API_DOCUMENTATION.md` for detailed endpoint documentation with request/response examples.**

---

## 🗄️ Database

### SQLite (Default)

The project uses SQLite for easy development:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/full/path/to/database/database.sqlite
```

### MySQL/PostgreSQL Setup

Update `.env` for production databases:

**MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=geospace_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

**PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=geospace_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### Database Schema

Key tables include:
- `users` - User accounts
- `user_details` - User profile information
- `company_details` - Company information
- `projects` - Project listings
- `timesheets` - Work hour tracking
- `contracts` - Freelancer contracts
- `payments` - Payment records
- `notifications` - User notifications
- `activity_logs` - Audit trail

### Seeded Data

After running `php artisan db:seed`, you'll have:
- 4 test user accounts (Admin, Freelancer, Company, Support)
- Sample projects
- Dropdown categories and values
- User roles and permissions
- Sample notifications

---

## ❓ Troubleshooting

### Installation Issues

**1. Missing Application Key**
```bash
php artisan key:generate
```

**2. Missing JWT Secret**
```bash
php artisan jwt:secret
```

**3. Composer Install Fails**
```bash
composer install --ignore-platform-reqs
# Or update PHP to 8.2+
```

**4. NPM Install Fails**
```bash
rm -rf node_modules package-lock.json
npm install
```

### Runtime Issues

**1. Database Connection Error**
```bash
# Check database file exists
ls -la database/database.sqlite

# Create if missing
touch database/database.sqlite

# Set permissions
chmod 664 database/database.sqlite
```

**2. Permission Denied Errors**
```bash
chmod -R 755 storage bootstrap/cache
sudo chown -R $USER:$USER storage bootstrap/cache
```

**3. Route Not Found (404)**
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

**4. Token Invalid/Expired (401)**
- Login again to get new token
- Check token is included in Authorization header
- Verify JWT secret is set: `php artisan config:show jwt`

**5. Internal Server Error (500)**
```bash
# Check logs
tail -50 storage/logs/laravel.log

# Enable debug mode temporarily
# Set APP_DEBUG=true in .env
```

**6. CORS Issues**
- Check `config/cors.php` settings
- Ensure frontend origin is in `allowed_origins`
- Check headers are properly set

**7. Database Migration Fails**
```bash
# Reset database
php artisan migrate:fresh --seed

# Or rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

### Performance Issues

**1. Slow Response Times**
```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

**2. Queue Jobs Not Processing**
```bash
# Check queue configuration
php artisan queue:work --queue=default --tries=3

# Restart queue worker
php artisan queue:restart
```

### Getting Help

1. **Check Logs:** `storage/logs/laravel.log`
2. **Monitor Real-time:** `php artisan pail`
3. **Review Docs:** See `API_DOCUMENTATION.md`
4. **Check Routes:** `php artisan route:list`
5. **Test in Tinker:** `php artisan tinker`

---

## 🚀 Next Steps

After successful installation:

1. ✅ Test the login endpoint with provided credentials
2. ✅ Explore the API documentation in `API_DOCUMENTATION.md`
3. ✅ Review the database structure
4. ✅ Set up your frontend to connect to the API
5. ✅ Configure environment variables for your needs
6. ✅ Set up proper database (MySQL/PostgreSQL) for production

---

## 📚 Additional Resources

- **Laravel Documentation:** https://laravel.com/docs
- **JWT Auth Package:** https://jwt-auth.readthedocs.io
- **API Best Practices:** RESTful conventions followed
- **Postman Collection:** (Add your collection URL here)

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

---

## 📄 License

[Your License Here]

---

**Happy Coding! 🚀**

*For detailed API endpoint documentation with request/response examples, see [API_DOCUMENTATION.md](API_DOCUMENTATION.md)*
