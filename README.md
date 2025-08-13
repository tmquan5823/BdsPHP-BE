# 🏠 BDS PHP Backend

Backend API cho hệ thống quản lý bất động sản được xây dựng bằng Laravel 12.

## 🚀 Yêu cầu hệ thống

-   **PHP**: ^8.2
-   **Composer**: ^2.0
-   **Database**: MySQL/PostgreSQL/SQLite
-   **Node.js**: ^18.0 (cho frontend assets)

## 📦 Cài đặt

### 1. Clone repository

```bash
git clone <repository-url>
cd BdsPHP-BE
```

### 2. Cài đặt dependencies

```bash
composer install
npm install
```

### 3. Cấu hình môi trường

```bash
# Copy file môi trường
cp .env.example .env

# Tạo application key
php artisan key:generate
```

### 4. Cấu hình database trong .env

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bds_php
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Chạy migration và seeder

```bash
# Tạo database tables
php artisan migrate

# Chạy seeder để tạo dữ liệu mẫu
php artisan db:seed
```

## 🏃‍♂️ Chạy ứng dụng

### Development server

```bash
# Chạy Laravel server
php artisan serve

# Chạy Vite dev server (trong terminal khác)
npm run dev

# Hoặc chạy cả hai cùng lúc
composer dev
```

### Production build

```bash
# Build assets cho production
npm run build
```

## 🎨 Code Style (PSR-12)

### Kiểm tra code style

```bash
# Kiểm tra xem có vi phạm PSR-12 không
composer psr12:check
```

### Tự động sửa code style

```bash
# Sửa tất cả files
composer psr12:fix

# Sửa và xem diff
composer psr12:fix-diff

# Sửa với verbose output
composer psr12:fix-verbose
```

## 📚 API Endpoints

### Authentication

```
POST   /api/login          - Đăng nhập
POST   /api/signin         - Đăng ký
POST   /api/logout         - Đăng xuất (cần auth)
GET    /api/me             - Thông tin user (cần auth)
```

### Properties (Bất động sản)

```
GET    /api/properties                    - Danh sách bất động sản
GET    /api/properties/{id}              - Chi tiết bất động sản
POST   /api/properties                    - Tạo bất động sản mới (cần auth)
PUT    /api/properties/{id}               - Cập nhật bất động sản (cần auth)
DELETE /api/properties/{id}               - Xóa bất động sản (cần auth)
POST   /api/properties/{id}/images       - Upload thêm ảnh (cần auth)
DELETE /api/properties/{id}/images/{image_id} - Xóa ảnh cụ thể (cần auth)
```

### User Management (cần auth)

```
GET    /api/users          - Danh sách users
GET    /api/users/{id}     - Chi tiết user
PUT    /api/users/{id}     - Cập nhật user
DELETE /api/users/{id}     - Xóa user
```

### Image Management (cần auth)

```
POST   /api/properties/{id}/images       - Upload thêm ảnh cho bất động sản
DELETE /api/properties/{id}/images/{image_id} - Xóa ảnh cụ thể của bất động sản
```

## 🔍 API Properties - Filter & Pagination

### Parameters

-   `per_page`: Số items/trang (1-100, mặc định: 10)
-   `city`: Thành phố
-   `status`: Trạng thái (available, sold, rented, pending)
-   `property_type`: Loại BDS (apartment, house, villa, office, land)
-   `min_price`: Giá tối thiểu
-   `max_price`: Giá tối đa

### Ví dụ sử dụng

```bash
# Lấy 20 căn hộ có sẵn ở Hồ Chí Minh
GET /api/properties?property_type=apartment&status=available&city=Hồ Chí Minh&per_page=20

# Lấy nhà riêng giá từ 1-5 tỷ
GET /api/properties?property_type=house&min_price=1000000000&max_price=5000000000

# Upload thêm ảnh cho bất động sản
POST /api/properties/1/images
Content-Type: multipart/form-data
Body: images[]=file1.jpg&images[]=file2.jpg

# Xóa ảnh cụ thể
DELETE /api/properties/1/images/5
```

### Response format

```json
{
    "status": "success",
    "message": "Lấy danh sách bất động sản thành công",
    "code": 200,
    "data": {
        "data": [
            {
                "id": 1,
                "title": "Căn hộ Quận 1",
                "price": 1500000000,
                "city": "Hồ Chí Minh",
                "status": "available",
                "images": [
                    {
                        "id": 1,
                        "image_path": "/storage/properties/1.jpg",
                        "is_primary": true
                    }
                ]
            }
        ],
        "meta": {
            "current_page": 1,
            "last_page": 5,
            "total": 45
        }
    }
}
```

## 🏗️ Cấu trúc Project

```
BdsPHP-BE/
├── app/
│   ├── Exceptions/          # Custom exceptions
│   ├── Http/
│   │   ├── Controllers/     # API Controllers
│   │   ├── Middlewares/     # HTTP Middlewares
│   │   └── Validations/     # Request validations
│   ├── Models/              # Eloquent models
│   ├── Providers/           # Service providers
│   ├── Repositories/        # Data access layer
│   └── Services/            # Business logic layer
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/             # Database seeders
│   └── factories/           # Model factories
├── routes/
│   └── api.php              # API routes
└── tests/                   # Test files
```

## 🔧 Troubleshooting

### Lỗi thường gặp

1. **"Class not found"**

    ```bash
    composer dump-autoload
    ```

2. **"Route not defined"**

    ```bash
    php artisan route:clear
    php artisan config:clear
    ```

3. **Database connection error**

    - Kiểm tra cấu hình trong `.env`
    - Đảm bảo database đã được tạo
    - Chạy `php artisan migrate:status`

4. **Permission denied**
    ```bash
    chmod -R 755 storage bootstrap/cache
    ```

## 📝 Contributing

1. Fork project
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Support

Nếu có vấn đề gì, vui lòng tạo issue hoặc liên hệ team development.

---

**Happy Coding! 🎉**
