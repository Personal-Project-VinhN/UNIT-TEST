# API Documentation - Accounting System

## Swagger/OpenAPI Documentation

Dự án đã có tài liệu Swagger/OpenAPI đầy đủ cho tất cả các API endpoints.

## Cách xem tài liệu API

### Option 1: Xem trực tiếp trong trình duyệt

1. Khởi động Laravel server:
```bash
php artisan serve
```

2. Mở trình duyệt và truy cập:
```
http://localhost:8000/api-docs.html
```

3. Hoặc xem file Swagger YAML trực tiếp:
```
http://localhost:8000/swagger.yaml
```

### Option 2: Sử dụng Swagger Editor Online

1. Truy cập: https://editor.swagger.io/
2. File > Import file
3. Chọn file `swagger.yaml` từ dự án

### Option 3: Sử dụng Postman

1. Mở Postman
2. Import > File
3. Chọn file `swagger.yaml`

## Các API Endpoints

### Accounts (Tài khoản)

- `GET /api/v1/accounts` - Lấy danh sách tất cả tài khoản
- `POST /api/v1/accounts` - Tạo tài khoản mới
- `GET /api/v1/accounts/{id}` - Lấy chi tiết tài khoản
- `PUT /api/v1/accounts/{id}` - Cập nhật tài khoản
- `DELETE /api/v1/accounts/{id}` - Xóa tài khoản
- `GET /api/v1/accounts/total-balance` - Lấy tổng số dư

### Categories (Danh mục)

- `GET /api/v1/categories` - Lấy danh sách tất cả danh mục
- `POST /api/v1/categories` - Tạo danh mục mới
- `GET /api/v1/categories/{id}` - Lấy chi tiết danh mục
- `PUT /api/v1/categories/{id}` - Cập nhật danh mục
- `DELETE /api/v1/categories/{id}` - Xóa danh mục
- `GET /api/v1/categories/type?type=revenue` - Lấy danh mục theo loại

### Transactions (Giao dịch)

- `GET /api/v1/transactions` - Lấy danh sách tất cả giao dịch
- `POST /api/v1/transactions` - Tạo giao dịch mới (thu/chi)
- `GET /api/v1/transactions/{id}` - Lấy chi tiết giao dịch
- `PUT /api/v1/transactions/{id}` - Cập nhật giao dịch
- `DELETE /api/v1/transactions/{id}` - Xóa giao dịch
- `GET /api/v1/transactions/revenue/total` - Lấy tổng thu
- `GET /api/v1/transactions/expense/total` - Lấy tổng chi
- `GET /api/v1/transactions/profit` - Lấy lợi nhuận

### Reports (Báo cáo)

- `GET /api/v1/reports/daily?date=2024-01-15` - Báo cáo theo ngày
- `GET /api/v1/reports/monthly?year=2024&month=1` - Báo cáo theo tháng
- `GET /api/v1/reports/yearly?year=2024` - Báo cáo theo năm
- `GET /api/v1/reports/date-range?start_date=2024-01-01&end_date=2024-01-31` - Báo cáo theo khoảng thời gian

## Data Models

### Account
```json
{
  "id": 1,
  "name": "Vietcombank Main",
  "account_type": "bank",
  "account_number": "1234567890",
  "bank_name": "Vietcombank",
  "balance": 100000000,
  "currency": "VND",
  "is_active": true
}
```

### Category
```json
{
  "id": 1,
  "name": "Software Development",
  "type": "revenue",
  "description": "Revenue from software development projects",
  "is_active": true
}
```

### Transaction
```json
{
  "id": 1,
  "account_id": 1,
  "category_id": 1,
  "type": "revenue",
  "amount": 50000000,
  "description": "Software development project payment",
  "transaction_date": "2024-01-15",
  "reference_number": "INV-2024-001",
  "notes": "Payment for completed project phase 1"
}
```

### Report
```json
{
  "start_date": "2024-01-01",
  "end_date": "2024-01-31",
  "total_revenue": 80000000,
  "total_expense": 23000000,
  "profit": 57000000,
  "transaction_count": 6,
  "revenue_by_category": {
    "1": 50000000,
    "2": 30000000
  },
  "expense_by_category": {
    "4": 15000000,
    "5": 5000000
  }
}
```

## Response Codes

- `200 OK` - Request thành công
- `201 Created` - Tạo resource thành công
- `400 Bad Request` - Dữ liệu không hợp lệ
- `404 Not Found` - Không tìm thấy resource
- `500 Internal Server Error` - Lỗi server

## Example Requests

### Tạo Account
```bash
curl -X POST http://localhost:8000/api/v1/accounts \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Vietcombank Main",
    "account_type": "bank",
    "account_number": "1234567890",
    "bank_name": "Vietcombank",
    "balance": 100000000,
    "currency": "VND"
  }'
```

### Tạo Transaction (Revenue)
```bash
curl -X POST http://localhost:8000/api/v1/transactions \
  -H "Content-Type: application/json" \
  -d '{
    "account_id": 1,
    "category_id": 1,
    "type": "revenue",
    "amount": 50000000,
    "description": "Software development project payment",
    "transaction_date": "2024-01-15",
    "reference_number": "INV-2024-001"
  }'
```

### Lấy báo cáo tháng
```bash
curl "http://localhost:8000/api/v1/reports/monthly?year=2024&month=1"
```

## Tác giả

**Gin** <gin_vn@haldata.net>
