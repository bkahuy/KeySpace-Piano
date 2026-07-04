<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Piano Store - Đồ Án Tốt Nghiệp')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .floating-contact {
            position: fixed;
            right: 24px;
            bottom: 90px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .floating-contact a {
            position: relative;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .floating-contact a:hover {
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.3);
        }

        .floating-contact-phone {
            background: #198754;
            font-size: 22px;
        }

        .floating-contact-zalo {
            background: #0068ff;
            font-size: 13px;
            font-weight: 700;
        }

        .floating-contact-tooltip {
            position: absolute;
            right: calc(100% + 12px);
            top: 50%;
            transform: translateY(-50%) translateX(8px);
            background: #212529;
            color: #fff;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
        }

        .floating-contact-tooltip::after {
            content: "";
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: transparent transparent transparent #212529;
        }

        .floating-contact-zalo:hover .floating-contact-tooltip,
        .floating-contact-zalo:focus .floating-contact-tooltip,
        .floating-contact-phone:hover .floating-contact-tooltip,
        .floating-contact-phone:focus .floating-contact-tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateY(-50%) translateX(0);
        }
    </style>

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

{{-- ================= HEADER / NAVBAR ================= --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fa-solid fa-music"></i> KEY SPACE
        </a>

        {{-- Nút menu cho mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Các link điều hướng --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">Trang chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Danh mục đàn
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0">
                        @if(isset($globalCategories))
                            @foreach($globalCategories as $cat)
                                <li>
                                    {{-- Truyền parameter category vào route cực kỳ thông minh --}}
                                    <a class="dropdown-item py-2" href="{{ route('product.index', ['category' => $cat->id]) }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 fw-bold text-primary" href="{{ route('product.index') }}">Xem tất cả đàn</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Thương hiệu
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0">
                        @if(isset($globalBrands))
                            @foreach($globalBrands as $brand)
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('product.index', ['brand' => $brand->id]) }}">
                                        {{ $brand->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tracking.index') }}">Tra cứu đơn hàng</a>
                </li>
            </ul>

            {{-- Khu vực Tìm kiếm & Giỏ hàng & User --}}
            <div class="d-flex align-items-center gap-3">
                <form action="{{ route('product.index') }}" method="GET" class="d-flex">
                    {{-- Đặt name="keyword" để Controller nhận diện được --}}
                    {{-- value="{{ request('keyword') }}" giúp giữ lại chữ khách vừa gõ trên ô tìm kiếm --}}
                    <input type="search" name="keyword" class="form-control me-2" placeholder="Tìm kiếm đàn piano, mã SKU..." value="{{ request('keyword') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>

                <a href="{{ route('cart.index') }}" class="btn btn-warning position-relative">
                    <i class="fa-solid fa-cart-shopping"></i>

                    {{-- Đếm số lượng loại sản phẩm trong giỏ hàng --}}
                    @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp

                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                {{-- Khu vực User Login / Logout --}}
                @auth
                    {{-- Nếu ĐÃ đăng nhập --}}
                    <div class="dropdown ms-3">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user me-1"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            {{-- Nút vào Admin chỉ hiện nếu role = admin --}}
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Vào trang Quản trị</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('customer.orders.history') }}">Trang cá nhân</a></li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    {{-- Nếu CHƯA đăng nhập --}}
                    <a href="{{ route('login') }}" class="btn btn-outline-light ms-3">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light">Đăng ký</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ================= MAIN CONTENT ================= --}}
{{-- Đây là phần cốt lõi. Nội dung của trang chủ, trang chi tiết... sẽ được "bơm" vào vị trí này --}}
<main class="flex-grow-1 bg-light">
    @yield('fullwidth')
    <div class="container py-4">
        @yield('content')
    </div>
</main>

{{-- ================= FOOTER ================= --}}
<footer class="bg-dark text-white pt-5 pb-4 mt-auto">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">KEY SPACE</h5>
                <p>Hệ thống phân phối đàn Piano chính hãng. Trải nghiệm âm thanh đỉnh cao dành cho nghệ sĩ. Đồ án tốt nghiệp ứng dụng Laravel 12 và MySQL.</p>
            </div>
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Hỗ trợ</h5>
                <p><a href="#" class="text-white text-decoration-none">Chính sách bảo hành</a></p>
                <p><a href="#" class="text-white text-decoration-none">Chính sách đổi trả</a></p>
                <p><a href="#" class="text-white text-decoration-none">Vận chuyển & Lắp đặt</a></p>
            </div>
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Liên hệ</h5>
                <p><i class="fas fa-home mr-3"></i> Hà Nội, Việt Nam</p>
                <p><i class="fas fa-envelope mr-3"></i> contact@pianostore.vn</p>
                <p><i class="fas fa-phone mr-3"></i> +84 987 654 321</p>
            </div>
        </div>
    </div>
</footer>

{{-- Nhúng Bootstrap 5 (JS) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- ================= KHU VỰC TOAST NOTIFICATION ================= --}}
@if(session('success'))
    {{-- Vị trí fixed ở góc dưới bên phải màn hình (bottom-0 end-0) --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 1055;">
        <div id="successToast" class="toast align-items-center text-bg-success border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body fs-6">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
            </div>
        </div>
    </div>

    {{-- Script nhỏ để tự động kích hoạt Toast khi có session success --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toastElement = document.getElementById('successToast');
            if (toastElement) {
                var toast = new bootstrap.Toast(toastElement);
                toast.show(); // Lệnh này làm popup trượt lên
            }
        });
    </script>
@endif

@if(session('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 1055;">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body fs-6">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastElement = document.getElementById('errorToast');
            if (toastElement) {
                var toast = new bootstrap.Toast(toastElement);
                toast.show();
            }
        });
    </script>
@endif

@stack('scripts')
{{-- ================= CONTACT BUTTONS ================= --}}
<div class="floating-contact">
    <a href="tel:+84837607568" class="floating-contact-phone" aria-label="Gọi điện">
        <i class="fa-solid fa-phone"></i>
        <span class="floating-contact-tooltip">083 7607 568</span>
    </a>


    <a href="https://zalo.me/84837607568" target="_blank" rel="noopener noreferrer" class="floating-contact-zalo">
        <i class="fa-brands fa-zalo">Zalo</i>
        <span class="floating-contact-tooltip">Bùi Khắc Huy</span>
    </a>
</div>



</body>
</html>
