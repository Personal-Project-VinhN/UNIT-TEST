# Hướng dẫn Setup dự án Laravel Accounting System

## Cấu trúc dự án Laravel

Dự án đã được tạo với cấu trúc Laravel chuẩn:

```
example_unit_test/
├── app/                          # Application code
│   ├── Console/                  # Artisan commands
│   ├── Exceptions/               # Exception handlers
│   ├── Http/                     # HTTP layer
│   │   ├── Controllers/          # Controllers
│   │   └── Middleware/           # Middleware
│   ├── Models/                   # Eloquent models
│   ├── Providers/                # Service providers
│   ├── Repositories/             # Repository pattern
│   └── Services/                 # Business logic
├── bootstrap/                    # Bootstrap files
│   ├── app.php                   # Application bootstrap
│   └── cache/                    # Cache files
├── config/                       # Configuration files
│   ├── app.php
│   ├── database.php
│   ├── cache.php
│   ├── session.php
│   └── ...
├── database/
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── mockData/                     # Mock data cho unit tests
├── public/                       # Public assets
│   └── index.php                 # Entry point
├── resources/                     # Views, assets
├── routes/                        # Route definitions
│   ├── api.php                   # API routes
│   ├── web.php                   # Web routes
│   └── console.php                # Console routes
├── storage/                      # Storage files
│   ├── app/
│   ├── framework/
│   └── logs/
├── tests/                        # Tests
│   └── Unit/
├── artisan                       # Artisan CLI
├── composer.json                 # Composer dependencies
└── phpunit.xml                   # PHPUnit config
```

## Các bước setup

### 1. Kiểm tra PHP version

```bash
php -v
# Cần PHP >= 8.1
```

### 2. Cài đặt Composer dependencies

```bash
composer install
```

Nếu chưa có Composer, tải về từ: https://getcomposer.org/

### 3. Tạo file .env

```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

### 4. Cấu hình database trong .env

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=accounting_system
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Tạo Application Key

```bash
php artisan key:generate
```

### 6. Tạo database

Tạo database trong MySQL:

```sql
CREATE DATABASE accounting_system;
```

### 7. Chạy migrations

```bash
php artisan migrate
```

### 8. Seed dữ liệu mẫu (tùy chọn)

```bash
php artisan db:seed
```

### 9. Khởi động server

```bash
php artisan serve
```

Truy cập: http://localhost:8000

## Kiểm tra cài đặt

### Test API endpoint

```bash
# Test root endpoint
curl http://localhost:8000

# Test API
curl http://localhost:8000/api/v1/accounts
```

### Chạy Unit Tests

```bash
# Chạy tất cả tests
php artisan test

# Hoặc sử dụng PHPUnit trực tiếp
vendor/bin/phpunit
```

## Troubleshooting

### Lỗi: Class not found

```bash
composer dump-autoload
```

### Lỗi: Permission denied (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
```

### Lỗi: Database connection

- Kiểm tra MySQL đã chạy chưa
- Kiểm tra credentials trong `.env`
- Đảm bảo database đã được tạo

### Lỗi: APP_KEY missing

```bash
php artisan key:generate
```

## Các lệnh Artisan hữu ích

```bash
# Xem danh sách routes
php artisan route:list

# Xem danh sách migrations
php artisan migrate:status

# Rollback migrations
php artisan migrate:rollback

# Xóa cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Tạo migration mới
php artisan make:migration create_table_name

# Tạo model
php artisan make:model ModelName

# Tạo controller
php artisan make:controller ControllerName
```

## Cấu trúc Repository Pattern

Dự án sử dụng Repository Pattern với:

- **Interfaces**: `app/Repositories/Contracts/`
- **Implementations**: `app/Repositories/`
- **Services**: `app/Services/` - Business logic
- **Controllers**: `app/Http/Controllers/` - HTTP handlers

## Mock Data cho Testing

Tất cả test data nằm trong `mockData/`:

- `mockData/transactions.php` - Test data cho transactions
- `mockData/categories.php` - Test data cho categories  
- `mockData/accounts.php` - Test data cho accounts
- `mockData/reports.php` - Test data cho reports

## Tác giả

**Gin** <gin_vn@haldata.net>
