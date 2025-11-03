# Hướng Dẫn Sử Dụng Test Reports

## 📊 Tổng Quan

Dự án đã được cấu hình để tự động generate các loại test reports khi chạy tests. Reports giúp bạn:
- Xem code coverage (phần trăm code được test)
- Tìm code chưa được test
- Tích hợp với CI/CD pipelines
- Đo lường chất lượng code

## 🚀 Cách Generate Reports

### Phương Pháp 1: Sử dụng Script (Khuyến nghị - Dễ nhất)

**Windows:**
```bash
.\deploy\generate-test-reports.bat
```

**Linux/Mac:**
```bash
chmod +x deploy/generate-test-reports.sh
./deploy/generate-test-reports.sh
```

Script sẽ:
1. Tạo thư mục `tests/results/` nếu chưa có
2. Chạy tất cả tests với coverage
3. Generate tất cả các loại reports
4. Tự động mở HTML coverage report trong browser

### Phương Pháp 2: Chạy thủ công

```bash
vendor/bin/phpunit \
    --coverage-html tests/results/coverage \
    --coverage-text \
    --coverage-clover tests/results/clover.xml \
    --log-junit tests/results/junit.xml \
    --testdox-html tests/results/testdox.html
```

### Phương Pháp 3: Chỉ generate một loại report

```bash
# Chỉ HTML coverage
vendor/bin/phpunit --coverage-html tests/results/coverage

# Chỉ text coverage
vendor/bin/phpunit --coverage-text

# Chỉ Clover XML
vendor/bin/phpunit --coverage-clover tests/results/clover.xml

# Chỉ JUnit XML
vendor/bin/phpunit --log-junit tests/results/junit.xml
```

## 📁 Cấu Trúc Reports

Sau khi generate, cấu trúc sẽ như sau:

```
tests/
└── results/
    ├── coverage/              # HTML Coverage Report
    │   └── index.html        # Mở file này trong browser
    ├── coverage.txt          # Text Coverage Report
    ├── clover.xml           # Clover XML (cho SonarQube, CodeClimate)
    ├── junit.xml            # JUnit XML (cho CI/CD)
    └── testdox.html         # TestDox HTML (readable format)
```

## 📖 Các Loại Reports

### 1. HTML Coverage Report ⭐ (Khuyến nghị)

**File:** `tests/results/coverage/index.html`

**Mô tả:** Report trực quan nhất, hiển thị line-by-line coverage.

**Cách xem:**
```bash
# Tự động mở (nếu dùng script)
.\deploy\generate-test-reports.bat

# Hoặc mở thủ công
start tests/results/coverage/index.html  # Windows
open tests/results/coverage/index.html   # Mac
xdg-open tests/results/coverage/index.html # Linux
```

**Bao gồm:**
- Tổng quan coverage (%, số dòng)
- Coverage theo file
- Coverage theo class
- Line-by-line coverage (màu xanh = covered, đỏ = not covered)
- Danh sách methods chưa được test

### 2. Text Coverage Report

**File:** `tests/results/coverage.txt` hoặc hiển thị trong terminal

**Mô tả:** Coverage summary dạng text, dễ đọc trong terminal.

**Ví dụ output:**
```
Code Coverage Report Summary:
  Classes:  50.00% (  5/ 10)
  Methods:  75.00% ( 30/ 40)
  Lines:    70.00% (350/500)
```

### 3. Clover XML

**File:** `tests/results/clover.xml`

**Mô tả:** XML format cho các tools như SonarQube, CodeClimate, PHPStorm.

**Sử dụng:**
- Upload lên SonarQube để phân tích code quality
- Tích hợp với CodeClimate
- Import vào PHPStorm để xem coverage

### 4. JUnit XML

**File:** `tests/results/junit.xml`

**Mô tả:** Standard XML format cho CI/CD pipelines.

**Sử dụng:**
- GitLab CI - tự động hiển thị test results
- GitHub Actions - hiển thị test summary
- Jenkins - tích hợp test results vào build

**Ví dụ GitLab CI:**
```yaml
test:
  script:
    - vendor/bin/phpunit --log-junit tests/results/junit.xml
  artifacts:
    reports:
      junit: tests/results/junit.xml
```

### 5. TestDox HTML

**File:** `tests/results/testdox.html`

**Mô tả:** Human-readable format, dễ đọc hơn JUnit XML.

**Bao gồm:**
- Danh sách tests đã chạy
- Kết quả (pass/fail)
- Thời gian chạy từng test

## 🎯 Code Coverage Standards

Dự án áp dụng các tiêu chuẩn sau:

| Loại Code | Target Coverage | Mô Tả |
|-----------|----------------|-------|
| **Services** | **90%+** | Business logic phải có coverage cao |
| **Repositories** | 80%+ | Data access layer |
| **Controllers** | 60%+ | API endpoints |
| **Models** | 70%+ | Data models |
| **Overall** | **70%+** | Tổng coverage toàn dự án |

### Cách đọc Coverage

- **90-100%**: ✅ Excellent - Code được test đầy đủ
- **70-89%**: ✅ Good - Coverage tốt
- **50-69%**: ⚠️ Acceptable - Cần cải thiện
- **< 50%**: ❌ Poor - Cần thêm tests

## 🔧 Tích Hợp CI/CD

### GitLab CI

```yaml
# .gitlab-ci.yml
test:
  stage: test
  script:
    - composer install
    - vendor/bin/phpunit \
        --coverage-clover tests/results/clover.xml \
        --log-junit tests/results/junit.xml
  coverage: '/^\s*Lines:\s*\d+\.\d+%/'
  artifacts:
    reports:
      junit: tests/results/junit.xml
    paths:
      - tests/results/clover.xml
```

### GitHub Actions

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: vendor/bin/phpunit \
          --coverage-clover tests/results/clover.xml \
          --log-junit tests/results/junit.xml
      - name: Upload coverage
        uses: codecov/codecov-action@v2
        with:
          file: tests/results/clover.xml
```

## 📝 Notes

- Reports được tự động ignore trong `.gitignore` (không commit lên git)
- HTML coverage report là cách tốt nhất để xem coverage local
- Clover XML và JUnit XML cần cho CI/CD integration
- Nên generate reports định kỳ để track coverage trends

## 🐛 Troubleshooting

### Lỗi: "Xdebug is required for code coverage"

**Giải pháp:**
```bash
# Kiểm tra Xdebug đã cài chưa
php -m | grep xdebug

# Nếu chưa có, cài Xdebug
# Windows (XAMPP/WAMP): Uncomment extension=xdebug trong php.ini
# Linux: pecl install xdebug
# Mac: brew install php-xdebug
```

### Reports không được tạo

**Kiểm tra:**
1. Thư mục `tests/results/` có tồn tại không
2. Quyền ghi file trong thư mục `tests/results/`
3. PHPUnit version >= 9.0 (cho coverage reports)

---

**Tác giả:** Gin <gin_vn@haldata.net>
