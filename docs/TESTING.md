# Hướng dẫn chạy Unit Tests

## Yêu cầu

- PHP >= 8.1
- Composer dependencies đã được cài đặt
- PHPUnit đã được cài đặt (có trong composer.json)

## Cài đặt dependencies

```bash
composer install
```

## Cấu hình Database cho Testing

Dự án sử dụng **SQLite in-memory** cho unit tests (không cần MySQL). File `phpunit.xml` đã được cấu hình để sử dụng `:memory:` database.

**Lưu ý:** Unit tests sử dụng **Mockery** để mock repositories, không cần database thật. Điều này giúp tests chạy nhanh và không phụ thuộc vào database.

## Chạy Tests

### 1. Chạy tất cả Unit tests

```bash
# Sử dụng PHPUnit trực tiếp (khuyến nghị)
vendor/bin/phpunit --testsuite=Unit

# Hoặc
vendor/bin/phpunit tests/Unit

# Hoặc sử dụng Artisan (Laravel)
php artisan test --testsuite=Unit
```

**Kết quả mong đợi:**
```
OK (17 tests, 43 assertions)
```

### 2. Chạy với output chi tiết

```bash
# TestDox format (dễ đọc)
vendor/bin/phpunit --testsuite=Unit --testdox

# Verbose output
vendor/bin/phpunit --testsuite=Unit --verbose
```

### 2. Chạy tests trong một thư mục cụ thể

```bash
# Chạy tất cả Unit tests
vendor/bin/phpunit tests/Unit

# Hoặc
php artisan test --testsuite=Unit
```

### 3. Chạy một test file cụ thể

```bash
# TransactionService tests
vendor/bin/phpunit tests/Unit/Services/TransactionServiceTest.php

# Hoặc
php artisan test tests/Unit/Services/TransactionServiceTest.php
```

### 4. Chạy một test method cụ thể

```bash
# Chạy method test_create_transaction_success
vendor/bin/phpunit --filter test_create_transaction_success

# Hoặc
php artisan test --filter test_create_transaction_success
```

### 5. Chạy với code coverage (nếu cần)

```bash
vendor/bin/phpunit --coverage-html coverage
```

## Cấu trúc Tests

```
tests/
└── Unit/
    └── Services/
        ├── TransactionServiceTest.php    # Tests cho TransactionService
        ├── CategoryServiceTest.php       # Tests cho CategoryService
        ├── AccountServiceTest.php        # Tests cho AccountService
        └── ReportServiceTest.php         # Tests cho ReportService
```

## Mock Data

Tất cả test data được lưu trong folder `mockData/`:

- `mockData/transactions.php` - Test data cho transactions
- `mockData/categories.php` - Test data cho categories
- `mockData/accounts.php` - Test data cho accounts
- `mockData/reports.php` - Test data cho reports

## Test Coverage

Dự án có **24 unit tests** với **57 assertions**, bao gồm:

### ✅ Success Cases (Happy Path)
- Create operations (transaction, category, account)
- Get operations (all, by type, active)
- Update operations
- Delete operations
- Calculate totals (revenue, expense, profit, balance)
- Generate reports (daily, monthly, date range)

### ❌ Failed Cases (Error Handling)
- **Transaction Service:**
  - Create transaction with non-existent account
  - Update transaction that does not exist
  - Delete transaction that does not exist

- **Category Service:**
  - Update category that does not exist
  - Delete category that does not exist

- **Account Service:**
  - Update account that does not exist
  - Delete account that does not exist

- **Report Service:**
  - Get daily report with empty data

## Ví dụ Test

Mỗi test sử dụng:
- **Mockery** để mock repositories
- **Mock data** từ folder `mockData/`
- **Assertions** để kiểm tra kết quả

### Ví dụ Success Case:

```php
public function test_create_transaction_success(): void
{
    $mockData = require __DIR__ . '/../../../mockData/transactions.php';
    $transactionData = $mockData['valid_revenue_transaction'];
    
    // Mock repository
    $this->transactionRepository
        ->shouldReceive('create')
        ->andReturn($transaction);
    
    // Test service
    $result = $this->transactionService->createTransaction($transactionData);
    
    // Assert
    $this->assertInstanceOf(Transaction::class, $result);
}
```

### Ví dụ Failed Case:

```php
public function test_create_transaction_account_not_found(): void
{
    $mockData = require __DIR__ . '/../../../mockData/transactions.php';
    $transactionData = $mockData['valid_revenue_transaction'];
    
    // Mock repository to return null (account not found)
    $this->accountRepository
        ->shouldReceive('find')
        ->andReturn(null);
    
    // Expect exception
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Account not found');
    
    $this->transactionService->createTransaction($transactionData);
}
```

## Troubleshooting

### Lỗi: Class not found

```bash
composer dump-autoload
```

### Lỗi: Database connection

Kiểm tra `phpunit.xml` có cấu hình đúng không. Dự án sử dụng SQLite in-memory nên không cần tạo database.

### Lỗi: Mockery not found

```bash
composer require --dev mockery/mockery
```

## Test Reports

### Tại sao cần Test Reports?
- 📊 **Track test coverage** - Biết code nào chưa được test
- 🐛 **Identify untested code** - Tìm gaps trong testing
- 📝 **Generate documentation** - Test reports như documentation
- 🔄 **CI/CD integration** - Tích hợp với Jenkins, GitLab CI, GitHub Actions
- 📈 **Quality metrics** - Đo lường chất lượng code

### Các loại Reports

#### 1. HTML Coverage Report (Khuyến nghị cho local development)
```bash
vendor/bin/phpunit --coverage-html tests/results/coverage
```
Mở file: `tests/results/coverage/index.html` trong browser để xem coverage trực quan.

#### 2. Text Coverage Report
```bash
vendor/bin/phpunit --coverage-text
```
Hiển thị coverage summary trong terminal.

#### 3. Clover XML (Cho CI/CD tools như SonarQube)
```bash
vendor/bin/phpunit --coverage-clover tests/results/clover.xml
```

#### 4. JUnit XML (Cho CI/CD như Jenkins, GitLab CI)
```bash
vendor/bin/phpunit --log-junit tests/results/junit.xml
```

### Generate tất cả Reports (Khuyến nghị)

**Windows:**
```bash
.\deploy\generate-test-reports.bat
```

**Linux/Mac:**
```bash
./deploy/generate-test-reports.sh
```

Hoặc thủ công:
```bash
vendor/bin/phpunit \
    --coverage-html tests/results/coverage \
    --coverage-text \
    --coverage-clover tests/results/clover.xml \
    --log-junit tests/results/junit.xml
```

### Xem Reports sau khi generate

Sau khi chạy scripts, các reports sẽ được tạo tại:
- **HTML Coverage**: `tests/results/coverage/index.html` (Mở trong browser)
- **Text Coverage**: `tests/results/coverage.txt`
- **Clover XML**: `tests/results/clover.xml`
- **JUnit XML**: `tests/results/junit.xml`
- **TestDox HTML**: `tests/results/testdox.html`

### Code Coverage Standards

- **Minimum**: 70% overall coverage
- **Critical Code**: 90%+ (Services, Business Logic)
- **Acceptable**: 60% (Controllers, Repositories)

## Chạy tests trong CI/CD

```bash
# Ví dụ cho GitHub Actions, GitLab CI, etc.
composer install --no-interaction --prefer-dist

# Run tests với reports
vendor/bin/phpunit \
    --coverage-clover tests/results/clover.xml \
    --log-junit tests/results/junit.xml

# Upload reports to CI/CD tools
# - clover.xml → SonarQube, CodeClimate
# - junit.xml → GitLab CI, GitHub Actions
```

## Tác giả

**Gin** <gin_vn@haldata.net>
