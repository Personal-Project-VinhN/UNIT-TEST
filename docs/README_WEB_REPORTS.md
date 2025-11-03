# Hướng Dẫn Truy Cập Test Reports Qua Web

## 🌐 Tổng Quan

Dự án đã được cấu hình để truy cập test reports qua web browser thay vì phải mở file HTML trực tiếp. Bạn có thể xem reports từ bất kỳ đâu thông qua web interface.

## 🚀 Cách Sử Dụng

### Bước 1: Khởi động Laravel Server

```bash
php artisan serve
```

Server sẽ chạy tại: `http://localhost:8000`

### Bước 2: Truy Cập Test Reports

#### 📊 Dashboard (Trang chủ Reports)
```
http://localhost:8000/test-reports
```

Trang này hiển thị:
- Danh sách tất cả reports có sẵn
- Trạng thái của từng report (Available/Not Found)
- Nút để xem/download reports

#### 📈 HTML Coverage Report
```
http://localhost:8000/test-reports/coverage
```
hoặc
```
http://localhost:8000/test-reports/coverage/index.html
```

#### 📋 TestDox Report
```
http://localhost:8000/test-reports/testdox
```

#### 📄 Download XML Reports
```
http://localhost:8000/test-reports/download/clover
http://localhost:8000/test-reports/download/junit
```

## 📁 Cấu Trúc Files

```
app/Http/Controllers/
└── TestReportController.php    # Controller xử lý requests

resources/views/test-reports/
├── index.blade.php             # Dashboard hiển thị tất cả reports
└── not-found.blade.php         # Error page khi report không tìm thấy

routes/web.php                  # Routes cho test reports
```

## 🔒 Bảo Mật

Hiện tại, reports có thể truy cập công khai. Để thêm bảo mật, bạn có thể:

### Option 1: Thêm Authentication Middleware

**Trong `app/Http/Controllers/TestReportController.php`:**

```php
public function __construct()
{
    // Chỉ user đã đăng nhập mới xem được
    $this->middleware('auth');
    
    // Hoặc chỉ admin
    // $this->middleware('auth:admin');
}
```

### Option 2: Thêm IP Whitelist

**Trong `app/Http/Controllers/TestReportController.php`:**

```php
public function __construct()
{
    $this->middleware(function ($request, $next) {
        $allowedIPs = ['127.0.0.1', '::1', '192.168.1.0/24'];
        $ip = $request->ip();
        
        if (!in_array($ip, $allowedIPs)) {
            abort(403, 'Access denied');
        }
        
        return $next($request);
    });
}
```

### Option 3: Environment-based Access

**Trong `routes/web.php`:**

```php
// Chỉ cho phép trong môi trường development
if (app()->environment('local', 'development')) {
    Route::prefix('test-reports')->group(function () {
        // Routes here
    });
}
```

## 🎨 Customization

### Thay Đổi Route Prefix

Nếu muốn đổi URL từ `/test-reports` sang tên khác:

**Trong `routes/web.php`:**

```php
Route::prefix('reports')->name('reports.')->group(function () {
    // Routes here
});
```

Bây giờ URL sẽ là: `http://localhost:8000/reports`

### Thêm Logo/Branding

Cập nhật file `resources/views/test-reports/index.blade.php` để thêm logo, header, footer theo brand của bạn.

## 🔧 Troubleshooting

### Lỗi: Route not found

**Nguyên nhân:** Routes chưa được load

**Giải pháp:**
```bash
php artisan route:clear
php artisan route:cache
```

### Lỗi: View not found

**Nguyên nhân:** View files chưa được tạo

**Giải pháp:**
- Kiểm tra thư mục `resources/views/test-reports/` có tồn tại
- Đảm bảo files `index.blade.php` và `not-found.blade.php` có trong thư mục

### Reports không hiển thị

**Nguyên nhân:** Reports chưa được generate

**Giải pháp:**
```bash
# Generate reports trước
.\deploy\generate-test-reports.bat

# Sau đó refresh browser
```

### CSS/JS không load trong Coverage Report

**Nguyên nhân:** Path routing không đúng

**Giải pháp:** Route `coverage/{path?}` đã được cấu hình để handle tất cả assets. Nếu vẫn lỗi, kiểm tra:
1. Route pattern: `->where('path', '.*')` đã được set chưa
2. File paths trong coverage report có đúng không

## 📱 Mobile Friendly

Dashboard đã được thiết kế responsive, có thể xem trên mobile:
- Grid layout tự động adapt
- Touch-friendly buttons
- Readable font sizes

## 🔄 Auto-refresh

Để tự động refresh khi reports được update, bạn có thể thêm JavaScript vào `index.blade.php`:

```javascript
// Auto refresh mỗi 30 giây
setInterval(function() {
    location.reload();
}, 30000);
```

## 📊 Integration với CI/CD

Nếu bạn muốn tự động deploy reports sau khi CI/CD chạy tests:

1. Generate reports trong CI/CD pipeline
2. Copy reports lên server
3. Access qua web interface để xem results

---

**Tác giả:** Gin <gin_vn@haldata.net>
