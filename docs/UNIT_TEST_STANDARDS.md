# UNIT TEST STANDARDS & BEST PRACTICES

## 📋 Mục Lục
1. [Naming Conventions](#1-naming-conventions)
2. [Test Structure (AAA Pattern)](#2-test-structure-aaa-pattern)
3. [Test Organization](#3-test-organization)
4. [Mocking Best Practices](#4-mocking-best-practices)
5. [Assertions](#5-assertions)
6. [Test Reports](#6-test-reports)
7. [Code Coverage](#7-code-coverage)
8. [Best Practices Checklist](#8-best-practices-checklist)
9. [Common Mistakes to Avoid](#9-common-mistakes-to-avoid)

---

## 1. Naming Conventions

### Test Class Names
- ✅ **Must end with `Test`**
- ✅ Should mirror source class structure
- ✅ Example: `TransactionService` → `TransactionServiceTest`

```php
// ✅ ĐÚNG
class TransactionServiceTest extends TestCase

// ❌ SAI
class TransactionServiceTesting extends TestCase
class TransactionServiceTests extends TestCase
```

### Test Method Names
- ✅ **Must start with `test_` prefix** hoặc sử dụng annotation `@test`
- ✅ Use descriptive names: `test_<method>_<scenario>_<expected>`
- ✅ Follow snake_case convention

```php
// ✅ ĐÚNG
public function test_create_transaction_success(): void
public function test_create_transaction_account_not_found(): void
public function test_get_total_revenue_with_valid_dates(): void

/** @test */
public function it_creates_a_transaction_successfully(): void

// ❌ SAI
public function testCreate(): void // Quá ngắn, không mô tả
public function createTransaction(): void // Thiếu prefix test_
```

### File Names
- ✅ Test file name phải khớp với class name
- ✅ Example: `TransactionServiceTest.php` cho class `TransactionServiceTest`

---

## 2. Test Structure (AAA Pattern)

Mỗi test method nên tuân theo **AAA Pattern** (Arrange-Act-Assert):

### Arrange (Chuẩn bị)
- Setup test data
- Configure mocks
- Prepare environment

### Act (Thực hiện)
- Execute the method under test
- **Chỉ MỘT hành động** per test

### Assert (Kiểm tra)
- Verify results
- Check side effects
- Validate exceptions

### Ví dụ:

```php
public function test_create_transaction_success(): void
{
    // ========== ARRANGE ==========
    // Chuẩn bị dữ liệu test
    $mockData = require __DIR__ . '/../../../mockData/transactions.php';
    $transactionData = $mockData['valid_revenue_transaction'];
    
    // Setup mocks
    $account = new Account();
    $account->id = $transactionData['account_id'];
    
    $this->accountRepository
        ->shouldReceive('find')
        ->with($transactionData['account_id'])
        ->once()
        ->andReturn($account);
    
    // ========== ACT ==========
    // Thực hiện hành động cần test (chỉ một hành động)
    $result = $this->transactionService->createTransaction($transactionData);
    
    // ========== ASSERT ==========
    // Kiểm tra kết quả
    $this->assertInstanceOf(Transaction::class, $result);
    $this->assertEquals($transactionData['amount'], $result->amount);
    $this->assertEquals('revenue', $result->type);
}
```

---

## 3. Test Organization

### Directory Structure

```
tests/
├── Unit/                      # Unit tests (isolated, fast)
│   ├── Services/
│   │   ├── TransactionServiceTest.php
│   │   ├── CategoryServiceTest.php
│   │   ├── AccountServiceTest.php
│   │   └── ReportServiceTest.php
│   ├── Repositories/          # Repository tests
│   ├── Models/                # Model tests (if needed)
│   └── Helpers/               # Helper function tests
│
├── Feature/                   # Feature tests (integration)
│   ├── Api/
│   │   └── TransactionApiTest.php
│   └── Web/
│       └── DashboardTest.php
│
├── TestCase.php               # Base test case
├── CreatesApplication.php     # Application factory trait
│
└── results/                   # Test reports (gitignored)
    ├── coverage/              # HTML coverage report
    ├── junit.xml             # JUnit XML for CI/CD
    ├── clover.xml            # Clover XML for CI/CD
    ├── coverage.txt           # Text coverage report
    └── testdox.html          # TestDox HTML report
```

### Test Class Structure

```php
<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;
use App\Services\TransactionService;

/**
 * Unit tests for TransactionService
 * 
 * @author Gin<gin_vn@haldata.net>
 * @lastupdate Gin<gin_vn@haldata.net>
 */
class TransactionServiceTest extends TestCase
{
    // ========== 1. PROPERTIES ==========
    protected TransactionService $service;
    protected $repository;
    
    // ========== 2. SETUP ==========
    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup mocks
        $this->repository = Mockery::mock(RepositoryInterface::class);
        
        // Create service instance
        $this->service = new TransactionService($this->repository);
    }
    
    // ========== 3. SUCCESS CASES ==========
    public function test_create_transaction_success(): void {}
    public function test_get_total_revenue(): void {}
    
    // ========== 4. FAILURE CASES ==========
    public function test_create_transaction_account_not_found(): void {}
    public function test_update_transaction_not_found(): void {}
    
    // ========== 5. EDGE CASES ==========
    public function test_create_transaction_with_zero_amount(): void {}
    public function test_get_profit_with_no_transactions(): void {}
    
    // ========== 6. CLEANUP ==========
    protected function tearDown(): void
    {
        Mockery::close(); // Cleanup mocks
        parent::tearDown();
    }
}
```

### Test Method Organization trong Class
1. **Setup methods** (setUp, tearDown)
2. **Success cases** (happy path)
3. **Failure cases** (error handling)
4. **Edge cases** (boundary conditions)

---

## 4. Mocking Best Practices

### Use Interfaces for Dependencies

```php
// ✅ ĐÚNG: Mock interface
use App\Repositories\Contracts\RepositoryInterface;

$this->repository = Mockery::mock(RepositoryInterface::class);

// ❌ SAI: Mock concrete class (khó test, tight coupling)
$this->repository = Mockery::mock(Repository::class);
```

### Specify Expectations Clearly

```php
// ✅ ĐÚNG: Rõ ràng, cụ thể
$this->repository
    ->shouldReceive('find')
    ->with($id)                    // Expect exact parameter
    ->once()                       // Call exactly once
    ->andReturn($expectedResult);  // Return specific value

// ❌ SAI: Quá mơ hồ
$this->repository
    ->shouldReceive('find')
    ->andReturn($expectedResult);
```

### Use Data Providers for Multiple Scenarios

```php
/**
 * @dataProvider transactionProvider
 */
public function test_create_transaction($data, $expected): void
{
    $this->repository
        ->shouldReceive('create')
        ->andReturn($expected);
    
    $result = $this->service->createTransaction($data);
    
    $this->assertEquals($expected->amount, $result->amount);
}

public function transactionProvider(): array
{
    return [
        'revenue transaction' => [
            'data' => ['type' => 'revenue', 'amount' => 10000000],
            'expected' => new Transaction(['type' => 'revenue', 'amount' => 10000000])
        ],
        'expense transaction' => [
            'data' => ['type' => 'expense', 'amount' => 5000000],
            'expected' => new Transaction(['type' => 'expense', 'amount' => 5000000])
        ],
    ];
}
```

### Mock Only External Dependencies

```php
// ✅ ĐÚNG: Chỉ mock external dependencies
$this->repository = Mockery::mock(RepositoryInterface::class);
$this->externalApi = Mockery::mock(ApiClient::class);

// ❌ SAI: Không mock internal/helper methods
$this->service = Mockery::mock(Service::class);
$this->service->shouldReceive('helperMethod'); // Don't mock what you're testing!
```

---

## 5. Assertions

### Use Specific Assertions

```php
// ✅ ĐÚNG: Specific assertions
$this->assertEquals($expected, $actual);
$this->assertInstanceOf(Transaction::class, $result);
$this->assertCount(3, $collection);
$this->assertTrue($condition);
$this->assertNull($value);
$this->assertContains($item, $collection);

// ❌ SAI: Too generic
$this->assertTrue($result !== null);        // Use assertNotNull()
$this->assertTrue($result instanceof Transaction); // Use assertInstanceOf()
```

### Provide Clear Assert Messages

```php
// ✅ ĐÚNG: Có message rõ ràng
$this->assertEquals(
    $expected,
    $actual,
    'Transaction amount should match expected value'
);

// Khi test fail, message sẽ giúp debug nhanh hơn
```

### One Assert Per Concept

```php
// ✅ ĐÚNG: Nhiều assertions cho cùng một concept (transaction creation)
public function test_create_transaction_success(): void
{
    $result = $this->service->createTransaction($data);
    
    $this->assertInstanceOf(Transaction::class, $result);
    $this->assertEquals('revenue', $result->type);
    $this->assertEquals(10000000, $result->amount);
    $this->assertNotNull($result->id);
}

// ❌ SAI: Quá nhiều concepts trong một test
public function test_everything(): void
{
    $this->service->createTransaction($data);
    $this->service->updateTransaction($id, $data);
    $this->service->deleteTransaction($id);
    // Quá nhiều hành động, khó debug khi fail
}
```

---

## 6. Test Reports

### Why Test Reports are Important
- 📊 **Track test coverage** - Biết code nào chưa được test
- 🐛 **Identify untested code** - Tìm gaps trong testing
- 📝 **Generate documentation** - Test reports như documentation
- 🔄 **CI/CD integration** - Tích hợp với Jenkins, GitLab CI, GitHub Actions
- 📈 **Quality metrics** - Đo lường chất lượng code

### Types of Reports

#### 1. **JUnit XML** - For CI/CD Integration
- Format: XML
- Used by: Jenkins, GitLab CI, GitHub Actions, CircleCI
- Location: `tests/results/junit.xml`

#### 2. **HTML Coverage Report** - Visual Code Coverage
- Format: HTML (interactive)
- Shows: Line-by-line coverage, uncovered code
- Location: `tests/results/coverage/index.html`
- ✅ **Recommended for local development**

#### 3. **Text Coverage Report** - Console Output
- Format: Plain text
- Shows: Coverage summary in terminal
- Location: `tests/results/coverage.txt`

#### 4. **Clover XML** - For CI/CD Tools
- Format: XML
- Used by: SonarQube, CodeClimate, PHPStorm
- Location: `tests/results/clover.xml`

#### 5. **TestDox HTML** - Human Readable Format
- Format: HTML
- Shows: Test names in readable format
- Location: `tests/results/testdox.html`

### Generating Reports

#### Basic Commands

```bash
# Run tests only (no coverage)
vendor/bin/phpunit --testsuite=Unit

# Run tests with HTML coverage report
vendor/bin/phpunit --coverage-html tests/results/coverage

# Run tests with text coverage
vendor/bin/phpunit --coverage-text

# Run tests with all reports (recommended)
vendor/bin/phpunit \
    --coverage-html tests/results/coverage \
    --coverage-text \
    --coverage-clover tests/results/clover.xml \
    --log-junit tests/results/junit.xml
```

#### Using Scripts (Easier)

**Windows:**
```batch
.\deploy\generate-test-reports.bat
```

**Linux/Mac:**
```bash
./deploy/generate-test-reports.sh
```

### PHPUnit Configuration

Reports được cấu hình trong `phpunit.xml`:

```xml
<logging>
    <junit outputFile="tests/results/junit.xml"/>
    <testdoxHtml outputFile="tests/results/testdox.html"/>
    <coverage>
        <report>
            <html outputDirectory="tests/results/coverage"/>
            <text outputFile="tests/results/coverage.txt"/>
            <clover outputFile="tests/results/clover.xml"/>
        </report>
    </coverage>
</logging>
```

---

## 7. Code Coverage

### Coverage Targets

- **Minimum**: **70%** overall coverage
- **Critical Code**: **90%+** (Services, Business Logic)
- **Acceptable**: **60%** (Controllers, Repositories)

### What to Cover

✅ **Must Cover:**
- Service layer (business logic)
- Complex calculations
- Error handling paths
- Edge cases

⚠️ **Optional:**
- Simple getters/setters
- Model factories
- Basic CRUD operations

❌ **Exclude from Coverage:**
- Service providers
- Middleware (unless has business logic)
- Exception classes
- Configuration files

### Viewing Coverage Reports

1. **HTML Report** (Recommended):
   ```bash
   vendor/bin/phpunit --coverage-html tests/results/coverage
   # Open: tests/results/coverage/index.html
   ```

2. **Text Report**:
   ```bash
   vendor/bin/phpunit --coverage-text
   ```

3. **In IDE** (PHPStorm):
   - Run tests with coverage
   - Coverage is shown inline in editor

---

## 8. Best Practices Checklist

Khi viết unit tests, đảm bảo:

### Naming & Structure
- [ ] Test class name ends with `Test`
- [ ] Test method starts with `test_` prefix
- [ ] Test name clearly describes scenario
- [ ] Test follows AAA pattern (Arrange-Act-Assert)

### Test Quality
- [ ] Tests are isolated (no dependencies between tests)
- [ ] All external dependencies are mocked
- [ ] Test data is in separate files (`mockData/`)
- [ ] Both success and failure cases are tested
- [ ] Edge cases are considered

### Performance
- [ ] Tests run fast (< 1 second per test)
- [ ] No database access in unit tests
- [ ] Uses in-memory database (SQLite `:memory:`) if needed

### Coverage & Reports
- [ ] Code coverage reports are generated
- [ ] Coverage meets minimum threshold (70%)
- [ ] Critical code has high coverage (90%+)

### CI/CD Integration
- [ ] Tests are part of CI/CD pipeline
- [ ] Test reports are generated in CI/CD
- [ ] Build fails if tests fail

---

## 9. Common Mistakes to Avoid

### ❌ Testing Implementation Details

```php
// ❌ SAI: Testing internal implementation
public function test_service_has_repository(): void
{
    $this->assertTrue(isset($this->service->repository));
}

// ✅ ĐÚNG: Testing behavior
public function test_service_returns_data(): void
{
    $result = $this->service->getData();
    $this->assertNotNull($result);
}
```

### ❌ Too Many Mocks

```php
// ❌ SAI: Over-mocking (không cần thiết)
$this->mockA->shouldReceive('methodA')->andReturn();
$this->mockB->shouldReceive('methodB')->andReturn();
$this->mockC->shouldReceive('methodC')->andReturn();
$this->mockD->shouldReceive('methodD')->andReturn();

// ✅ ĐÚNG: Chỉ mock external dependencies
$this->repository->shouldReceive('find')->andReturn($data);
```

### ❌ Ignoring Edge Cases

```php
// ❌ SAI: Chỉ test happy path
public function test_calculate_total(): void
{
    $result = $this->service->calculate([10, 20, 30]);
    $this->assertEquals(60, $result);
}

// ✅ ĐÚNG: Test cả edge cases
public function test_calculate_total_with_empty_array(): void
{
    $result = $this->service->calculate([]);
    $this->assertEquals(0, $result);
}

public function test_calculate_total_with_negative_numbers(): void
{
    $result = $this->service->calculate([10, -5, 20]);
    $this->assertEquals(25, $result);
}
```

### ❌ Testing Multiple Things in One Test

```php
// ❌ SAI: Quá nhiều hành động
public function test_transaction_operations(): void
{
    $transaction = $this->service->create($data);
    $this->service->update($transaction->id, $newData);
    $this->service->delete($transaction->id);
    // Quá nhiều, khó debug khi fail
}

// ✅ ĐÚNG: Một hành động per test
public function test_create_transaction(): void {}
public function test_update_transaction(): void {}
public function test_delete_transaction(): void {}
```

### ❌ Not Cleaning Up

```php
// ❌ SAI: Không cleanup mocks
protected function tearDown(): void
{
    parent::tearDown();
    // Missing Mockery::close()
}

// ✅ ĐÚNG: Cleanup properly
protected function tearDown(): void
{
    Mockery::close(); // Important!
    parent::tearDown();
}
```

---

## 📚 Tài Liệu Tham Khảo

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Mockery Documentation](http://docs.mockery.io/)
- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [Test-Driven Development by Example](https://www.amazon.com/Test-Driven-Development-Kent-Beck/dp/0321146530)

---

## 👨‍💻 Tác Giả

**Gin** <gin_vn@haldata.net>

**Last Updated:** 2025-01-03
