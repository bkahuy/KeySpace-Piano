<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Piano Store')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar {
            width: 250px;
            background-color: #212529;
            color: white;

            /* DÙNG FIXED ĐỂ GHIM CHẾT VÀO GÓC TRÁI MÀN HÌNH */
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh; /* Luôn luôn cao bằng 100% chiều cao màn hình hiển thị */
            overflow-y: auto; /* Nếu menu nhiều quá thì tự có thanh cuộn bên trong menu */
            z-index: 1040; /* Đảm bảo sidebar luôn nằm trên cùng, không bị nội dung khác đè lên */
        }

        .sidebar a { color: #adb5bd; text-decoration: none; padding: 15px 20px; display: block; border-bottom: 1px solid #343a40; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background-color: #343a40; color: #fff; border-left: 4px solid #0d6efd; }

        .content {
            background-color: #f8f9fa;
            min-height: 100vh;

            /* Vì sidebar đã bị ghim nổi lên trên, ta phải đẩy content sang phải 250px để không bị đè */
            margin-left: 250px;
            width: calc(100% - 250px);
        }
        :root {
            --admin-sidebar-width: 250px;
        }

        body {
            overflow-x: hidden;
        }

        .sidebar {
            width: var(--admin-sidebar-width);
            transition: transform 0.25s ease;
        }

        .content {
            margin-left: var(--admin-sidebar-width);
            width: calc(100% - var(--admin-sidebar-width));
            min-width: 0;
        }

        .admin-sidebar-toggle,
        .admin-sidebar-backdrop {
            display: none;
        }

        @media (max-width: 991.98px) {
            body {
                display: block !important;
            }

            .sidebar {
                width: min(var(--admin-sidebar-width), 86vw);
                transform: translateX(-100%);
            }

            body.admin-sidebar-open .sidebar {
                transform: translateX(0);
            }

            .admin-sidebar-backdrop {
                position: fixed;
                inset: 0;
                display: block;
                background: rgba(0, 0, 0, 0.45);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.25s ease;
                z-index: 1030;
            }

            body.admin-sidebar-open .admin-sidebar-backdrop {
                opacity: 1;
                pointer-events: auto;
            }

            .content {
                margin-left: 0;
                width: 100%;
            }

            .admin-sidebar-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                flex: 0 0 auto;
            }

            .navbar .container-fluid {
                gap: 0.75rem;
            }

            .navbar-brand {
                flex: 1 1 180px;
                white-space: normal;
                line-height: 1.25;
            }
        }

        @media (max-width: 575.98px) {
            .navbar {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .navbar .container-fluid {
                align-items: flex-start !important;
            }

            .navbar .d-flex.align-items-center.gap-3 {
                width: 100%;
                justify-content: space-between;
                align-items: flex-start !important;
                gap: 0.75rem !important;
                flex-wrap: wrap;
            }

            .container-fluid.px-4 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .card-body {
                min-width: 0;
            }

            .card-body h3 {
                overflow-wrap: anywhere;
            }
        }
    </style>
</head>
<body class="d-flex">

{{-- CỘT TRÁI: SIDEBAR --}}
<div class="sidebar shadow">
    <div class="text-center py-4 fs-4 fw-bold border-bottom border-secondary">
        <i class="fa-solid fa-music text-primary me-2"></i> PIANO ADMIN
    </div>
    <div class="mt-3">
        {{-- Dùng request()->routeIs() để kiểm tra route hiện tại --}}

        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge me-2"></i> Tổng quan
        </a>

        {{-- Dùng products.* để nó sáng đèn ở cả trang Danh sách, Thêm mới, Sửa... --}}
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open me-2"></i> Quản lý Đàn Piano
        </a>

        <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fa-solid fa-list me-2"></i> Danh mục đàn
        </a>

        <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="fa-solid fa-cart-flatbed me-2"></i> Đơn hàng
        </a>

        <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users me-2"></i> Khách hàng
        </a>

        <a href="{{ route('promotions.index') }}" class="{{ request()->routeIs('promotions.*') ? 'active' : '' }}">
            <i class="fa-solid fa-ticket me-2"></i> Khuyến mãi
        </a>


        <a href="{{ route('reviews.index') }}" class="nav-link ...">
            <i class="fa-solid fa-star text-warning"></i> Đánh giá
        </a>
    </div>
</div>

{{-- CỘT PHẢI: NỘI DUNG CHÍNH --}}
<button type="button" class="admin-sidebar-backdrop border-0 p-0" aria-label="Close menu"></button>
<div class="content">
    {{-- Thanh Topbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 px-4 py-3">
        <div class="container-fluid">
            <button type="button" class="admin-sidebar-toggle btn btn-outline-secondary d-lg-none" aria-label="Open menu">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="navbar-brand mb-0 h1">@yield('title')</span>

            <div class="d-flex align-items-center gap-3">
                <span class="text-muted">Xin chào, <strong>{{ Auth::user()->name }}</strong></span>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Nội dung thay đổi ở đây --}}
    <div class="container-fluid px-4">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
{{-- ================= KHU VỰC TOAST NOTIFICATION CHO ADMIN ================= --}}
<div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 1055;">

    {{-- Toast Thành công --}}
    @if(session('success'))
        <div id="successToast" class="toast align-items-center text-bg-success border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body fs-6">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
            </div>
        </div>
    @endif

    {{-- Toast Lỗi --}}
    @if(session('error'))
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body fs-6">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
            </div>
        </div>
    @endif
</div>

{{-- Script kích hoạt Toast --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var successElement = document.getElementById('successToast');
        if (successElement) {
            var toast1 = new bootstrap.Toast(successElement);
            toast1.show();
        }

        var errorElement = document.getElementById('errorToast');
        if (errorElement) {
            var toast2 = new bootstrap.Toast(errorElement);
            toast2.show();
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var toggleButton = document.querySelector('.admin-sidebar-toggle');
        var backdrop = document.querySelector('.admin-sidebar-backdrop');
        var sidebarLinks = document.querySelectorAll('.sidebar a');

        function closeSidebar() {
            document.body.classList.remove('admin-sidebar-open');
        }

        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                document.body.classList.toggle('admin-sidebar-open');
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', closeSidebar);
        }

        sidebarLinks.forEach(function(link) {
            link.addEventListener('click', closeSidebar);
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });
    });
</script>
</body>
</html>
