# Bugs Đã Thêm Vào Code (Để Test Unit Tests)

⚠️ **LƯU Ý:** Các bugs này được thêm vào có mục đích để kiểm tra khả năng phát hiện lỗi của unit tests. 

## Danh Sách Bugs

### 1. TransactionService::getTotalRevenue() - **BUG**
**File:** `app/Services/TransactionService.php` (dòng 127)

**Mô tả:** Tính sai tổng revenue - nhân với 2 thay vì lấy tổng trực tiếp

**Code sai:**
```php
return $transactions
    ->where('type', 'revenue')
    ->sum('amount') * 2; // BUG: Nhân với 2
```

**Code đúng:**
```php
return $transactions
    ->where('type', 'revenue')
    ->sum('amount');
```

**Test bị fail:** `test_get_total_revenue`

---

### 2. TransactionService::getTotalExpense() - **BUG**
**File:** `app/Services/TransactionService.php` (dòng 145)

**Mô tả:** Tính sai tổng expense - lấy tổng revenue thay vì expense

**Code sai:**
```php
return $transactions
    ->where('type', 'revenue') // BUG: Sai type
    ->sum('amount');
```

**Code đúng:**
```php
return $transactions
    ->where('type', 'expense')
    ->sum('amount');
```

**Test bị fail:** `test_get_total_expense`

---

### 3. TransactionService::getProfit() - **BUG**
**File:** `app/Services/TransactionService.php` (dòng 162)

**Mô tả:** Tính sai profit - cộng thay vì trừ

**Code sai:**
```php
return $revenue + $expense; // BUG: Cộng thay vì trừ
```

**Code đúng:**
```php
return $revenue - $expense;
```

**Test bị fail:** `test_get_profit`

---

### 4. AccountService::getTotalBalance() - **BUG**
**File:** `app/Services/AccountService.php` (dòng 102)

**Mô tả:** Tính sai tổng balance - trừ 10000000 thay vì lấy tổng trực tiếp

**Code sai:**
```php
return $accounts->sum('balance') - 10000000; // BUG: Trừ sai số
```

**Code đúng:**
```php
return $accounts->sum('balance');
```

**Test bị fail:** `test_get_total_balance`

---

### 5. CategoryService::createCategory() - **BUG**
**File:** `app/Services/CategoryService.php` (dòng 67-68)

**Mô tả:** Luôn trả về null thay vì category đã tạo

**Code sai:**
```php
$this->categoryRepository->create($data);
return null; // BUG: Trả về null
```

**Code đúng:**
```php
return $this->categoryRepository->create($data);
```

**Test bị fail:** `test_create_category`

---

### 6. ReportService::getDailyReport() - **BUG**
**File:** `app/Services/ReportService.php` (dòng 43-44)

**Mô tả:** Trả về sai start_date và end_date

**Code sai:**
```php
$report = $this->getReportByDateRange($date, $date);
$report['start_date'] = '2024-01-01'; // BUG: Sai date
$report['end_date'] = '2024-12-31'; // BUG: Sai date
return $report;
```

**Code đúng:**
```php
return $this->getReportByDateRange($date, $date);
```

**Test bị fail:** 
- `test_get_daily_report`
- `test_get_daily_report_empty_data`

---

## Kết Quả Test Sau Khi Thêm Bugs

```
Tests: 24, Assertions: 46, Failures: 7

FAILED TESTS:
✘ Get total revenue
✘ Get total expense  
✘ Get profit
✘ Get total balance
✘ Create category
✘ Get daily report
✘ Get daily report empty data
```

## Cách Sửa Lỗi

Để sửa các bugs này, chỉ cần revert lại code về đúng như ban đầu (xóa các dòng có comment `// BUG` và sửa lại logic).

Sau khi sửa, chạy lại tests:
```bash
# Sử dụng script
.\deploy\RUN_TESTS.bat    # Windows
./deploy/RUN_TESTS.sh      # Linux/Mac

# Hoặc trực tiếp
vendor/bin/phpunit --testsuite=Unit --testdox
```

Tất cả tests sẽ pass trở lại! ✅
