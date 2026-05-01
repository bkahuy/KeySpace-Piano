<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tài khoản') | KEY SPACE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: #f4efe6;
            font-family: 'Inter', Arial, sans-serif;
            color: #1d1a16;
        }

        .auth-header {
            background: #171512;
            min-height: 66px;
            border-bottom: 1px solid rgba(190, 146, 72, .25);
        }

        .brand-logo {
            color: #fffaf2;
            font-size: 21px;
            font-weight: 800;
            text-decoration: none;
        }

        .brand-logo i,
        .text-gold {
            color: #b88a3d !important;
        }

        .auth-nav a {
            color: rgba(255, 255, 255, .72);
            text-decoration: none;
            font-weight: 600;
            margin-left: 22px;
        }

        .auth-nav a:hover {
            color: #d0a45c;
        }

        .auth-wrapper {
            min-height: calc(100vh - 66px);
            display: flex;
            align-items: center;
            padding: 42px 0;
        }

        .auth-box {
            max-width: 1040px;
            margin: auto;
            background: #fffdf8;
            border: 1px solid rgba(31, 25, 17, .08);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 22px 55px rgba(31, 25, 17, .13);
        }

        .auth-side {
            min-height: 560px;
            padding: 54px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background:
                linear-gradient(90deg, rgba(17, 14, 10, .88), rgba(17, 14, 10, .68)),
                url("https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?auto=format&fit=crop&w=1200&q=80");
            background-size: cover;
            background-position: center;
            color: #fffaf2;
        }

        .auth-side .tag {
            color: #d0a45c;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .auth-side h1 {
            max-width: 420px;
            font-size: 34px;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 18px;
        }

        .auth-side p {
            max-width: 430px;
            color: rgba(255, 250, 242, .78);
            line-height: 1.7;
            margin-bottom: 0;
        }

        .auth-benefits {
            margin-top: 34px;
            display: grid;
            gap: 14px;
        }

        .auth-benefit {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0;
            background: transparent;
            border: 0;
        }

        .auth-benefit i {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #d0a45c;
            border: 1px solid rgba(208, 164, 92, .45);
            border-radius: 50%;
            font-size: 14px;
            margin: 0;
        }

        .auth-benefit strong {
            font-size: 14px;
            font-weight: 700;
        }

        .auth-form-area {
            padding: 56px 58px;
            display: flex;
            align-items: center;
            background: #fffdf8;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            margin: auto;
        }

        .auth-card h2 {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .auth-card .subtitle {
            color: #766f66;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 700;
        }

        .form-control {
            min-height: 48px;
            border-radius: 6px;
            border-color: #d9d0c1;
            background: #fff;
        }

        .form-control:focus {
            border-color: #b88a3d;
            box-shadow: 0 0 0 .18rem rgba(184, 138, 61, .16);
        }

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #fff inset;
            -webkit-text-fill-color: #1d1a16;
        }

        .btn-auth {
            min-height: 50px;
            border-radius: 6px;
            background: #1d1a16;
            border: 1px solid #1d1a16;
            color: #fffaf2;
            font-weight: 800;
            text-transform: none;
        }

        .btn-auth:hover {
            background: #b88a3d;
            border-color: #b88a3d;
            color: #1d1a16;
        }

        .auth-link {
            color: #9a6d28;
            font-weight: 800;
            text-decoration: none;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 991px) {
            .auth-side {
                min-height: 380px;
                padding: 38px;
            }

            .auth-form-area {
                padding: 38px;
            }
        }

        @media (max-width: 576px) {
            .auth-wrapper {
                padding: 20px 0;
            }

            .auth-side {
                min-height: 320px;
                padding: 28px;
            }

            .auth-side h1 {
                font-size: 28px;
            }

            .auth-form-area {
                padding: 28px 22px;
            }

            .auth-nav {
                margin-top: 10px;
            }

            .auth-nav a {
                margin-left: 0;
                margin-right: 14px;
            }
        }

    </style>
</head>
<body>

<header class="auth-header d-flex align-items-center">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
        <a href="{{ url('/') }}" class="brand-logo">
            <i class="fa-solid fa-music"></i> KEY SPACE
        </a>

        <nav class="auth-nav">
            <a href="{{ url('/') }}">Trang chủ</a>
            <a href="{{ route('login') }}">Đăng nhập</a>
            <a href="{{ route('register') }}">Đăng ký</a>
        </nav>
    </div>
</header>

<main class="auth-wrapper">
    <div class="container">
        <div class="auth-box row g-0">
            <section class="col-lg-6 auth-side">
                <div class="tag">KEY SPACE PIANO</div>

                <h1>@yield('auth_heading', 'Không gian dành cho người yêu piano')</h1>

                <p>
                    @yield('auth_description', 'Đăng nhập hoặc tạo tài khoản để lưu mẫu đàn yêu thích, theo dõi đơn hàng và nhận tư vấn chọn piano phù hợp.')
                </p>

                <div class="auth-benefits">
                    <div class="auth-benefit">
                        <i class="fa-solid fa-certificate"></i>
                        <strong>Đàn chính hãng</strong>
                    </div>

                    <div class="auth-benefit">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                        <strong>Bảo hành rõ ràng</strong>
                    </div>

                    <div class="auth-benefit">
                        <i class="fa-solid fa-truck-fast"></i>
                        <strong>Lắp đặt tận nơi</strong>
                    </div>
                </div>
            </section>

            <section class="col-lg-6 auth-form-area">
                <div class="auth-card">
                    @yield('content')
                </div>
            </section>
        </div>
    </div>
</main>
<script>
    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-toggle-password]');

        if (!button) return;

        const inputId = button.getAttribute('data-toggle-password');
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');

        if (!input) return;

        const isPassword = input.type === 'password';

        input.type = isPassword ? 'text' : 'password';

        icon.classList.toggle('fa-eye', isPassword);
        icon.classList.toggle('fa-eye-slash', !isPassword);
    });
</script>

</body>
</html>
