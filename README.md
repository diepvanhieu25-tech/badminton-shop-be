# DSPORT NEXUS • Badminton E-Commerce Platform

[![Next.js](https://img.shields.io/badge/Frontend-Next.js%2016-black?style=flat-square&logo=next.js)](https://nextjs.org)
[![Laravel](https://img.shields.io/badge/Backend-Laravel%2012-red?style=flat-square&logo=laravel)](https://laravel.com)
[![Docker](https://img.shields.io/badge/DevOps-Docker%20Compose-blue?style=flat-square&logo=docker)](https://www.docker.com)
[![Tailwind CSS](https://img.shields.io/badge/Styling-Tailwind%20CSS-38bdf8?style=flat-square&logo=tailwind-css)](https://tailwindcss.com)
[![Tests](https://img.shields.io/badge/Tests-16%20Passed%20(66%20Assertions)-success?style=flat-square)](https://phpunit.de)

**DSPORT NEXUS** là nền tảng thương mại điện tử chuyên nghiệp cung cấp dụng cụ thể thao & thiết bị cầu lông chính hãng hàng đầu Việt Nam. Hệ thống bao gồm Website khách hàng (Next.js 16 App Router) với ngôn ngữ thiết kế hiện đại, bảng màu thương hiệu `#13634B`, hình ảnh 100% thiết bị cầu lông thực tế (Yonex, Li-Ning, Mizuno, Kumpoo, Kuno), kết nối hoàn chỉnh với Backend RESTful API và Cổng Quản trị Admin (Laravel 12).

---

## 🏸 Tính Năng Nổi Bật

### 1. Phía Khách Hàng (Client Storefront)
* **Nhận diện thương hiệu & Bố cục hiện đại:**
  * Màu chủ đạo xanh đậm `#13634B` (Dark Emerald) trên nền sáng tinh tế (`#f8fafc`).
  * Hero Slider tự động trình chiếu siêu phẩm thi đấu (Yonex Nanoflare 1000Z, Li-Ning Halbertec 8000, Giày Yonex 65Z3).
  * 100% hình ảnh sản phẩm & biến thể cầu lông chụp thật từ kho dữ liệu cục bộ (không dùng ảnh stock).
* **Danh mục & Tìm kiếm nâng cao:**
  * Lọc theo thương hiệu (Yonex, Li-Ning, Mizuno, Kumpoo, Kuno), danh mục sản phẩm, mức giá, sản phẩm nổi bật.
  * Tìm kiếm từ khóa theo thời gian thực.
* **Chi tiết sản phẩm & Biến thể phong phú:**
  * Đầy đủ biến thể màu sắc, trọng lượng (3U, 4U, 5U), chu vi cán (G5, G6), size giày thi đấu (40, 41, 42) và size trang phục (S, M, L, XL).
  * Chuyển đổi biến thể mượt mà kèm đồng bộ hình ảnh và số lượng tồn kho tương ứng.
  * Dịch vụ căng cước chuẩn BWF (lựa chọn loại cước và mức căng theo lbs).
  * Đề xuất sản phẩm liên quan theo danh mục.
* **Giỏ hàng & Luồng thanh toán mượt mà:**
  * Mini Cart (Drawer trượt từ cạnh phải) kèm thanh tiến trình Miễn phí vận chuyển.
  * Hỗ trợ lưu giỏ hàng khách vãng lai và tự động đồng bộ lên tài khoản khi đăng nhập.
  * Tích hợp cổng thanh toán trực tuyến **VNPay (Sandbox + Trình mô phỏng Sandbox nội bộ)**, Thanh toán khi nhận hàng (COD), Chuyển khoản ngân hàng.
  * Trang theo dõi đơn hàng thời gian thực (`/orders/:code`).

### 2. Phía Quản Trị (Admin Portal)
* **Dashboard thống kê:** Doanh thu tổng hợp, đơn hàng mới, sản phẩm bán chạy, cảnh báo hàng tồn thấp.
* **Quản lý sản phẩm & biến thể:** Thêm mới/sửa/xóa sản phẩm, tạo bảng biến thể SKU động, quản lý album ảnh sản phẩm.
* **Quản lý đơn hàng:** Tiếp nhận đơn, cập nhật trạng thái đơn hàng (Pending -> Processing -> Shipping -> Completed / Cancelled), xuất thông tin vận chuyển.
* **Quản lý danh mục & thương hiệu:** Cập nhật icon, banner danh mục, logo thương hiệu.
* **Quản lý người dùng & bảo mật:** Phân quyền vai trò (Admin / Customer), quản lý trạng thái tài khoản.

---

## 🛠️ Công Nghệ Sử Dụng (Tech Stack)

| Tầng Hệ Thống | Công Nghệ / Thư Viện |
|:---|:---|
| **Frontend Client** | Next.js 16 (App Router, Turbopack), React 19, TypeScript, Tailwind CSS, Lucide / Material Symbols |
| **Backend API & Admin** | Laravel 12 (PHP 8.2), Laravel Sanctum (Token Auth), Blade Templates, Eloquent ORM |
| **Database & Cache** | MySQL 8.0, Redis (PHP-Redis extension) |
| **DevOps & Container** | Docker, Docker Compose, Nginx 1.27 (Reverse Proxy & Static Asset Server) |
| **Thanh Toán** | Cổng VNPay (HMAC-SHA512 checksum), VNPay Sandbox Simulator |

---

## 📁 Cấu Trúc Thư Mục Dự Án

```text
badminton-shop-be/
├── backend/                             # Laravel 12 Source Code
│   ├── app/
│   │   ├── Enums/                       # UserRole, OrderStatus, PaymentStatus, etc.
│   │   ├── Http/Controllers/Api/V1/     # RESTful APIs (Auth, Product, Cart, Order, Payment)
│   │   ├── Http/Controllers/Admin/      # Admin Blade Controllers (Product, Order, Category, etc.)
│   │   ├── Http/Resources/Api/          # API JSON Transformers
│   │   ├── Models/                      # Eloquent Models (Product, Variant, Order, Payment, etc.)
│   │   ├── Repositories/                # Repository Pattern (Eloquent Repositories & Interfaces)
│   │   └── Services/Api/                # Business Logic (OrderService, CartService, VnpayService)
│   ├── database/
│   │   ├── migrations/                  # Schema migrations
│   │   └── seeders/DatabaseSeeder.php   # Dữ liệu mẫu chuẩn 16 sản phẩm, 40 biến thể, 5 hãng
│   ├── resources/views/admin/           # Blade views giao diện quản trị Admin
│   ├── routes/                          # api.php (v1 routes), web.php (admin routes)
│   └── tests/Feature/                   # PHPUnit Feature Tests (AdminWebTest, EcommerceApiTest)
├── sports-ecommerce/                    # Next.js 16 Frontend
│   ├── public/                          # Static assets, logo thương hiệu
│   └── src/
│       ├── app/                         # Next.js App Router (pages: /, /products, /cart, /checkout, etc.)
│       │   ├── orders/payment/vnpay/simulate/ # Trình giả lập VNPay Sandbox
│       │   └── ...
│       ├── components/                  # Header, Hero, MiniCart, ProductCard, Footer, Breadcrumb, etc.
│       ├── context/                     # AuthContext, CartContext (State Management)
│       ├── services/api.ts              # API client wrapper
│       └── types/api.ts                 # TypeScript type definitions
├── docker-compose.yml                   # Docker orchestration (Nginx, App, DB, Redis)
└── README.md                            # Tài liệu dự án
```

---

## 🚀 Hướng Dẫn Cài Đặt & Khởi Chạy

### 1. Yêu Cầu Môi Trường
* [Docker](https://docs.docker.com/get-docker/) & [Docker Compose](https://docs.docker.com/compose/)
* [Node.js](https://nodejs.org) >= 20.x và npm >= 10.x

---

### 2. Khởi Động Backend (Docker)
Từ thư mục gốc dự án:

```bash
# 1. Khởi chạy các container (Nginx, Laravel App, MySQL, Redis)
docker compose up -d

# 2. Cài đặt Composer dependencies (nếu chạy lần đầu)
docker exec shop_be_app composer install

# 3. Tạo symlink phục vụ ảnh tĩnh (Bắt buộc để tải ảnh sản phẩm)
docker exec shop_be_app php artisan storage:link

# 4. Chạy migration và seed dữ liệu mẫu sản phẩm thực tế
docker exec shop_be_app php artisan migrate:fresh --seed --force

# 5. Xóa cache cấu hình
docker exec shop_be_app php artisan config:clear
docker exec shop_be_app php artisan cache:clear
```

* **Backend Nginx & API:** `http://localhost:8088`
* **Admin Portal:** `http://localhost:8088/admin`
* **Database MySQL:** `localhost:3308` (User: `shop`, Pass: `shop`, DB: `shop`)

---

### 3. Khởi Động Frontend (Next.js)

```bash
# Di chuyển vào thư mục frontend
cd sports-ecommerce

# Cài đặt thư viện
npm install

# Khởi chạy máy chủ phát triển
npm run dev
```

* **Client Storefront:** `http://localhost:3000`

---

## 🔑 Tài Khoản Mẫu Trải Nghiệm (Demo Accounts)

| Vai Trò | Cổng Đăng Nhập | Email | Mật Khẩu | Quyền Hạn |
|:---|:---|:---|:---|:---|
| **Khách Hàng (Customer)** | `http://localhost:3000/login` | `buyer@badminton.vn` | `SecurePassword123!` | Mua hàng, giỏ hàng, đặt đơn VNPay, xem lịch sử |
| **Quản Trị Viên (Admin)** | `http://localhost:8088/admin/login` | `admin@badminton.vn` | `AdminPassword123!` | Toàn quyền quản trị Dashboard, Sản phẩm, Đơn hàng |

> 💡 *Trang thanh toán có sẵn nút **"Đăng nhập nhanh mẫu (buyer@badminton.vn)"** giúp trải nghiệm luồng mua hàng và VNPay ngay lập tức mà không cần gõ phím.*

---

## 💳 Hướng Dẫn Thử Nghiệm Thanh Toán VNPay

1. Vào trang chi tiết bất kỳ sản phẩm (ví dụ: *Vợt Yonex Nanoflare 1000Z*).
2. Chọn biến thể mong muốn và nhấn **"Mua ngay"** hoặc thêm vào giỏ rồi chọn **"Tiến hành thanh toán"**.
3. Tại trang Checkout (`/checkout`), điền thông tin người nhận và tích chọn phương thức **"Ví điện tử VNPay"**.
4. Nhấn **"Đặt hàng ngay"**:
   * Hệ thống tự động chuyển tiếp đến **Cổng Giả Lập VNPay Sandbox** (`/orders/payment/vnpay/simulate?code=ORD-...`).
   * Nhấn nút 🟢 **"Xác nhận Thanh toán Thành công (Test Mã 00)"**: Webhook backend sẽ cập nhật trạng thái đơn sang `processing` và thanh toán thành `paid`, tự động chuyển về trang `/order-success` kèm huy hiệu thành công.
   * Hoặc nhấn nút 🔴 **"Hủy giao dịch / Thử nghiệm thất bại (Test Mã 24)"** để kiểm tra kịch bản hủy và thử lại đơn hàng.

---

## 🧪 Kiểm Thử Tự Động (Automated Testing)

### Kiểm thử Backend (PHPUnit 16 Suites)
```bash
docker exec shop_be_app php artisan test
```
```text
PASS Tests\Unit\ExampleTest
PASS Tests\Feature\AdminWebTest (10 tests passed)
PASS Tests\Feature\EcommerceApiTest (4 tests passed)
PASS Tests\Feature\ExampleTest (1 test passed)

Tests: 16 passed (66 assertions)
```

### Kiểm thử Frontend Build (Next.js)
```bash
cd sports-ecommerce && npm run build
```
```text
✓ Compiled successfully in 5.6s
✓ Finished TypeScript in 3.9s
✓ Generating static pages (13/13)
```

---

## 📜 Giấy Phép & Bản Quyền
Dự án được xây dựng và phát triển cho hệ sinh thái phân phối thiết bị cầu lông thể thao chuyên nghiệp **DSPORT NEXUS**.
Mọi thắc mắc và đóng góp xin gửi về: `support@badminton-shop.com`.
