# Accounting System - Laravel Project

Hệ thống quản lý kế toán cho doanh nghiệp IT - Quản lý thu chi (Revenue & Expense Management)

## 📋 Mục Lục

- [Tổng Quan](#tổng-quan)
- [Cài Đặt](#cài-đặt)
- [Cấu Trúc Dự Án](#cấu-trúc-dự-án)
- [Tài Liệu](#tài-liệu)
- [Chạy Tests](#chạy-tests)
- [API Documentation](#api-documentation)

## 🎯 Tổng Quan

Dự án sử dụng Laravel 10.x với:
- Repository Pattern (5 repositories)
- Service Layer
- Unit Testing với PHPUnit và Mockery
- API Documentation với Swagger/OpenAPI

## 🚀 Cài Đặt

### Yêu Cầu

- PHP >= 8.1
- Composer
- MySQL/MariaDB hoặc SQLite

### Các Bước

1. Clone repository:
```bash
git clone <repository-url>
cd example_unit_test
```

2. Cài đặt dependencies:
```bash
composer install
```

3. Cấu hình environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Cấu hình database trong `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=accounting_system
DB_USERNAME=root
DB_PASSWORD=
```

5. Chạy migrations:
```bash
php artisan migrate
```

6. Seed dữ liệu mẫu (tùy chọn):
```bash
php artisan db:seed
```

7. Khởi động server:
```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 📁 Cấu Trúc Dự Án

```
project/
├── app/
│   ├── Http/Controllers/    # API Controllers
│   ├── Models/              # Eloquent Models
│   ├── Repositories/        # Repository Pattern
│   │   ├── Contracts/       # Repository Interfaces
│   │   └── *.php            # Repository Implementations
│   ├── Services/            # Business Logic Layer
│   └── Providers/           # Service Providers
│
├── database/
│   ├── migrations/          # Database Migrations
│   └── seeders/             # Database Seeders
│
├── docs/                    # 📚 Documentation Files
│   ├── README.md            # Documentation index
│   ├── API_DOCUMENTATION.md # API guide
│   ├── TESTING.md           # Testing guide
│   ├── UNIT_TEST_STANDARDS.md
│   └── swagger.yaml         # OpenAPI spec
│
├── deploy/                  # 🚀 Deploy Scripts
│   ├── README.md            # Deploy scripts guide
│   ├── RUN_TESTS.bat/sh     # Run tests
│   └── generate-test-reports.bat/sh
│
├── mockData/                # Test Data
│   ├── transactions.php
│   ├── categories.php
│   ├── accounts.php
│   └── reports.php
│
├── tests/                   # Unit Tests
│   ├── Unit/Services/       # Service tests
│   └── results/             # Test reports
│
└── routes/
    ├── api.php              # API routes
    └── web.php              # Web routes
```

## 📚 Tài Liệu

Tất cả tài liệu được lưu trong thư mục [`docs/`](docs/):

- **Xem chi tiết**: Xem file [`docs/README.md`](docs/README.md)

### Các Tài Liệu Chính:

- [`docs/README.md`](docs/README.md) - Tổng quan dự án
- [`docs/SETUP.md`](docs/SETUP.md) - Hướng dẫn setup
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md) - Kiến trúc hệ thống
- [`docs/API_DOCUMENTATION.md`](docs/API_DOCUMENTATION.md) - API Documentation
- [`docs/TESTING.md`](docs/TESTING.md) - Hướng dẫn testing
- [`docs/UNIT_TEST_STANDARDS.md`](docs/UNIT_TEST_STANDARDS.md) - Chuẩn unit testing

## 🧪 Chạy Tests

### Cách 1: Sử dụng Script (Khuyến nghị)

**Windows:**
```bash
.\deploy\RUN_TESTS.bat
```

**Linux/Mac:**
```bash
./deploy/RUN_TESTS.sh
```

### Cách 2: Chạy trực tiếp

```bash
vendor/bin/phpunit --testsuite=Unit --testdox
```

### Generate Test Reports

```bash
.\deploy\generate-test-reports.bat    # Windows
./deploy/generate-test-reports.sh      # Linux/Mac
```

Xem reports tại: `http://localhost:8000/test-reports`

## 📖 API Documentation

- **Swagger UI**: `http://localhost:8000/api-docs.html`
- **Swagger YAML**: `http://localhost:8000/swagger.yaml`
- **Chi tiết**: Xem [`docs/API_DOCUMENTATION.md`](docs/API_DOCUMENTATION.md)

## 🔧 Scripts & Deployment

Tất cả scripts được lưu trong thư mục [`deploy/`](deploy/):

- **Xem chi tiết**: Xem file [`deploy/README.md`](deploy/README.md)

## 📊 Test Coverage

- **24 Unit Tests** với **57 Assertions**
- Code Coverage: Xem tại `http://localhost:8000/test-reports`

## 👨‍💻 Tác Giả

**Gin** <gin_vn@haldata.net>

---

**Version:** 1.0.0  
**Last Updated:** 2025-01-03

# UNIT-TEST
