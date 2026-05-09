# 🎹 KEY SPACE - Piano E-Commerce System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)


> Hệ thống thương mại điện tử chuyên phân phối đàn Piano chính hãng - Đồ án tốt nghiệp

## 📋 Mục lục

- [Giới thiệu](#-giới-thiệu)
- [Tính năng](#-tính-năng)
- [Công nghệ sử dụng](#️-công-nghệ-sử-dụng)
- [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
- [Cài đặt](#-cài-đặt)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [Tài khoản mẫu](#-tài-khoản-mẫu)
- [Database Schema](#-database-schema)
- [API Routes](#-api-routes)
- [Screenshots](#-screenshots)
- [Tác giả](#-tác-giả)
- [License](#-license)

## 🎯 Giới thiệu

**KEY SPACE** là hệ thống thương mại điện tử đầy đủ tính năng, chuyên cung cấp các loại đàn Piano chất lượng cao. Dự án được xây dựng bằng Laravel 12 với mục đích là đồ án tốt nghiệp, tích hợp đầy đủ các tính năng của một website bán hàng chuyên nghiệp.

### ✨ Điểm nổi bật

- 🛒 Hệ thống giỏ hàng với session storage
- 💳 Tích hợp thanh toán VNPay
- 🎟️ Hệ thống mã giảm giá & điểm thưởng
- 📦 Quản lý đơn hàng đa trạng thái
- ⭐ Đánh giá sản phẩm với hệ thống duyệt
- 📊 Dashboard quản trị với thống kê
- 🔍 Tìm kiếm & lọc sản phẩm nâng cao
- 📧 Gửi email xác nhận đơn hàng
- 🔐 Xác thực & phân quyền (Admin/Customer)

## 🚀 Tính năng

### 👥 Khách hàng

#### Xem & Tìm kiếm sản phẩm
- Trang chủ với sản phẩm nổi bật
- Danh sách sản phẩm theo danh mục, thương hiệu
- Tìm kiếm theo tên, SKU
- Lọc theo giá, tình trạng (mới/cũ)
- Sắp xếp theo giá, tên, mới nhất

#### Quản lý giỏ hàng
- Thêm/sửa/xóa sản phẩm
- Cập nhật số lượng tự động
- Kiểm tra tồn kho real-time
- Toast notification thông báo lỗi

#### Thanh toán
- Form thông tin giao hàng
- Áp dụng mã giảm giá
- Sử dụng điểm thưởng
- Nhiều phương thức thanh toán:
  - COD (Tiền mặt)
  - Chuyển khoản ngân hàng
  - VNPay
  - Momo

#### Quản lý đơn hàng
- Xem lịch sử đơn hàng
- Chi tiết đơn hàng
- Hủy đơn hàng (trạng thái pending)
- Tra cứu đơn hàng theo mã

#### Đánh giá sản phẩm
- Viết đánh giá cho sản phẩm đã mua
- Rating từ 1-5 sao
- Comment chi tiết

#### Tài khoản cá nhân
- Cập nhật thông tin
- Xem điểm thưởng
- Xem mã giảm giá khả dụng

### 🔧 Quản trị viên

#### Dashboard
- Thống kê doanh thu theo tháng
- Tổng quan đơn hàng
- Số lượng khách hàng
- Sản phẩm bán chạy

#### Quản lý sản phẩm
- CRUD sản phẩm
- Upload nhiều ảnh
- Quản lý tồn kho
- Kích hoạt/vô hiệu hóa

#### Quản lý đơn hàng
- Xem danh sách đơn hàng
- Cập nhật trạng thái:
  - Pending (Chờ xử lý)
  - Processing (Đang xử lý)
  - Shipping (Đang giao)
  - Delivered (Đã giao)
  - Cancelled (Đã hủy)
- Chi tiết đơn hàng

#### Quản lý danh mục
- CRUD danh mục
- Hỗ trợ danh mục đa cấp (parent-children)

#### Quản lý thương hiệu
- CRUD thương hiệu
- Upload logo

#### Quản lý khách hàng
- Xem danh sách khách hàng
- Xem lịch sử mua hàng
- Quản lý điểm thưởng

#### Quản lý đánh giá
- Duyệt/từ chối đánh giá
- Xóa đánh giá vi phạm

#### Quản lý khuyến mãi
- CRUD chương trình khuyến mãi
- Tạo mã giảm giá cho khách hàng
- Thiết lập:
  - Loại giảm giá (%, số tiền cố định)
  - Giá trị đơn hàng tối thiểu
  - Giảm tối đa
  - Thời gian áp dụng

## 🛠️ Công nghệ sử dụng

### Backend
- **Framework:** Laravel 12.x
- **PHP:** 8.2+
- **Database:** MySQL
- **Authentication:** Laravel Sanctum
- **Mail:** Laravel Mail

### Frontend
- **CSS Framework:** Bootstrap 5.3
- **Icons:** Font Awesome 6.4
- **Fonts:** Google Fonts (Poppins)
- **JavaScript:** Vanilla JS

### Payment Gateway
- VNPay API
- Momo API (tích hợp sẵn)

### Development Tools
- **Build Tool:** Vite 7.x
- **Package Manager:** Composer
- **Version Control:** Git

## 💻 Yêu cầu hệ thống

- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 18.x
- MySQL >= 8.0
- Extensions:
  - BCMath PHP Extension
  - Ctype PHP Extension
  - Fileinfo PHP Extension
  - JSON PHP Extension
  - Mbstring PHP Extension
  - OpenSSL PHP Extension
  - PDO PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
  - GD PHP Extension

## 📥 Cài đặt

### 1. Clone repository

```bash
git clone https://github.com/bkahuy/KeySpace-Piano.git
cd keyspace
```

### 2. Cài đặt dependencies

```bash
composer install
```

### 3. Cấu hình môi trường

```bash
# Copy file .env.example
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Cấu hình database

Mở file `.env` và cập nhật thông tin database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=keyspace_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Cấu hình Mail (Optional)

Để gửi email xác nhận đơn hàng:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 6. Cấu hình VNPay (Optional)

```env
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_RETURN_URL=http://localhost:8000/thanh-toan/vnpay/return
```

### 7. Chạy migration và seeder

```bash
# Tạo bảng và dữ liệu mẫu
php artisan migrate --seed

# Hoặc nếu muốn reset database
php artisan migrate:fresh --seed
```

### 8. Tạo symbolic link cho storage

```bash
php artisan storage:link
```

### 9. Build assets

```bash
npm run build
```

### 10. Chạy ứng dụng

```bash
# Development server
php artisan serve

```

Mở trình duyệt và truy cập: `http://localhost:8000`

## 📁 Cấu trúc dự án

```
keyspace/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controllers cho Admin
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── CustomerController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── PromotionController.php
│   │   │   │   └── ReviewController.php
│   │   │   ├── Auth/           # Controllers xác thực
│   │   │   │   └── AuthController.php
│   │   │   └── Customer/       # Controllers cho Customer
│   │   │       ├── CartController.php
│   │   │       ├── CheckoutController.php
│   │   │       ├── CustomerOrderController.php
│   │   │       ├── HomeController.php
│   │   │       ├── OrderTrackingController.php
│   │   │       ├── ProductController.php
│   │   │       └── ProfileController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── PreventBackHistory.php
│   ├── Mail/
│   │   └── OrderPlaced.php      # Email template
│   └── Models/
│       ├── Brand.php
│       ├── Category.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Product.php
│       ├── ProductImage.php
│       ├── Promotion.php
│       ├── Review.php
│       ├── User.php
│       └── UserCoupon.php
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── public/
│   └── storage/                 # Public storage (images)
├── resources/
│   └── views/
│       ├── admin/               # Admin views
│       ├── auth/                # Auth views
│       ├── customer/            # Customer views
│       ├── emails/              # Email templates
│       └── layout/              # Shared layouts
└── routes/
    └── web.php                  # Web routes
```

## 👤 Tài khoản mẫu

Sau khi chạy seeder, bạn có thể đăng nhập với các tài khoản sau:

### Admin
- **Email:** admin@pianostore.vn
- **Password:** 123456

### Customer
- **Email:** customer@gmail.com
- **Password:** 123456

## 🗄️ Database Schema

Hệ thống sử dụng 10 bảng chính:

### Core Tables
- **users** - Thông tin người dùng (admin, customer)
- **brands** - Thương hiệu đàn piano
- **categories** - Danh mục sản phẩm (hỗ trợ đa cấp)
- **products** - Sản phẩm
- **product_images** - Hình ảnh sản phẩm

### Order Tables
- **orders** - Đơn hàng
- **order_items** - Chi tiết đơn hàng

### Engagement Tables
- **reviews** - Đánh giá sản phẩm

### Promotion Tables
- **promotions** - Chương trình khuyến mãi
- **user_coupons** - Mã giảm giá của người dùng

### Relationships
- User `1 ----< *` Order
- User `1 ----< *` Review
- User `1 ----< *` UserCoupon
- Product `* >---- 1` Category
- Product `* >---- 1` Brand
- Product `1 ----< *` ProductImage
- Product `1 ----< *` Review
- Product `1 ----< *` OrderItem
- Order `1 ----< *` OrderItem
- Order `* >---- 0..1` UserCoupon
- Promotion `1 ----< *` UserCoupon

Xem chi tiết: [Class Diagram (PlantUML)](docs/class-diagram.puml)

## 🛣️ API Routes

### Public Routes
```
GET  /                          # Trang chủ
GET  /san-pham                  # Danh sách sản phẩm
GET  /san-pham/{slug}           # Chi tiết sản phẩm
GET  /tra-cuu-don-hang          # Tra cứu đơn hàng
POST /tra-cuu-don-hang          # Xử lý tra cứu
```

### Guest Routes (Chưa đăng nhập)
```
GET  /dang-nhap                 # Form đăng nhập
POST /dang-nhap                 # Xử lý đăng nhập
GET  /dang-ky                   # Form đăng ký
POST /dang-ky                   # Xử lý đăng ký
```

### Cart Routes
```
GET  /gio-hang                  # Xem giỏ hàng
POST /gio-hang/them             # Thêm vào giỏ
POST /gio-hang/cap-nhat         # Cập nhật số lượng
GET  /gio-hang/xoa/{id}         # Xóa sản phẩm
```

### Checkout Routes
```
GET  /thanh-toan                # Form thanh toán
POST /thanh-toan                # Xử lý thanh toán
GET  /thanh-toan/thanh-cong     # Trang thành công
GET  /thanh-toan/vnpay/return   # VNPay callback
```

### Customer Routes (Yêu cầu đăng nhập)
```
GET  /lich-su-mua-hang          # Lịch sử đơn hàng
GET  /chi-tiet-don-hang/{id}    # Chi tiết đơn hàng
PUT  /chi-tiet-don-hang/{id}/huy # Hủy đơn hàng
POST /products/{id}/review      # Đánh giá sản phẩm
PUT  /trang-ca-nhan             # Cập nhật thông tin
POST /dang-xuat                 # Đăng xuất
```

### Admin Routes (Yêu cầu role admin)
```
GET  /admin                     # Dashboard
# Products
GET    /admin/products          # Danh sách sản phẩm
GET    /admin/products/create   # Form thêm sản phẩm
POST   /admin/products          # Lưu sản phẩm
GET    /admin/products/{id}/edit # Form sửa sản phẩm
PUT    /admin/products/{id}     # Cập nhật sản phẩm
DELETE /admin/products/{id}     # Xóa sản phẩm

# Orders
GET    /admin/orders            # Danh sách đơn hàng
GET    /admin/orders/{id}       # Chi tiết đơn hàng
PUT    /admin/orders/{id}       # Cập nhật trạng thái

# Categories (Resource routes)
# Customers (Resource routes)
# Promotions (Resource routes)

# Reviews
GET    /admin/reviews           # Danh sách đánh giá
POST   /admin/reviews/{id}/toggle-status # Duyệt/từ chối
DELETE /admin/reviews/{id}      # Xóa đánh giá
```

## 📸 Screenshots

### Trang chủ
![Home Page](docs/screenshots/home.png)

### Chi tiết sản phẩm
![Product Detail](docs/screenshots/product-detail.png)

### Giỏ hàng
![Cart](docs/screenshots/cart.png)

### Admin Dashboard
![Admin Dashboard](docs/screenshots/admin-dashboard.png)

## 🤝 Đóng góp

Mọi đóng góp đều được chào đón! Hãy tạo issue hoặc pull request.

### Quy trình đóng góp

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## 👨‍💻 Tác giả

**Bùi Khắc Huy**
- Email: huybk.ph38783@fpt.edu.vn
- Phone: 083 760 7568
- Zalo: [083 760 7568](https://zalo.me/84837607568)

## 📝 License

Dự án này được phân phối dưới giấy phép [MIT License](LICENSE).

## 🙏 Lời cảm ơn

- Laravel Framework
- Bootstrap Team
- Font Awesome
- VNPay Vietnam
- Tất cả contributors và supporters

---

⭐ Nếu thấy dự án hữu ích, hãy cho một star nhé!
