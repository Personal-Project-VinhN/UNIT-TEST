# Deploy Scripts

Thư mục này chứa các scripts để deploy và chạy tests.

## 📋 Danh Sách Scripts

### Test Scripts

#### Windows
- **RUN_TESTS.bat** - Chạy unit tests (không có coverage)
- **generate-test-reports.bat** - Chạy tests và generate reports với code coverage

#### Linux/Mac
- **RUN_TESTS.sh** - Chạy unit tests (không có coverage)
- **generate-test-reports.sh** - Chạy tests và generate reports với code coverage

## 🚀 Cách Sử Dụng

### Chạy Tests

**Windows:**
```bash
.\deploy\RUN_TESTS.bat
```

**Linux/Mac:**
```bash
chmod +x deploy/RUN_TESTS.sh
./deploy/RUN_TESTS.sh
```

### Generate Test Reports

**Windows:**
```bash
.\deploy\generate-test-reports.bat
```

**Linux/Mac:**
```bash
chmod +x deploy/generate-test-reports.sh
./deploy/generate-test-reports.sh
```

## 📝 Lưu Ý

- Tất cả scripts tự động chuyển về thư mục gốc của project (parent directory)
- Đảm bảo đã cài đặt dependencies: `composer install`
- Đảm bảo PHPUnit đã được cài đặt trong `vendor/bin/phpunit`

---

**Tác giả:** Gin <gin_vn@haldata.net>

