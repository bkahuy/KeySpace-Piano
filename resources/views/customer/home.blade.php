@extends('layout.index')
@section('fullwidth')

    <section class="hero">
        <img
            src="{{ asset('storage/uploads/banner/piano_banner.png') }}"
            class="hero-img">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <span class="hero-kicker">Premium Piano Boutique</span>
            <h1>Key Space Piano</h1>
            <p>Không gian chọn đàn piano chính hãng cho người yêu âm nhạc và nghệ sĩ biểu diễn.</p>
            <a href="{{ route('product.index') }}" class="btn btn-luxury btn-lg">
                Khám phá bộ sưu tập
            </a>

        </div>
    </section>

@endsection
@section('content')
    <div class="container">
        {{-- DANH MỤC --}}
        <section class="category">
            <h2 class="section-title">
                Danh mục Piano
            </h2>
            <div class="row text-center">
                <div class="col">
                    <a href="{{ route('product.index', ['category' => '1']) }}" class="category-link">
                        <div class="category-box">
                            <i class="fa-solid fa-music category-icon"></i>
                            <h5>Đại dương cầm</h5>
                            <p>Biểu tượng của nghệ thuật và đẳng cấp.</p>
                        </div>
                    </a>

                </div>
                <div class="col">
                    <a href="{{ route('product.index', ['category' => '2']) }}" class="category-link">
                        <div class="category-box">
                            <i class="fa-solid fa-layer-group category-icon"></i>
                            <h5>Đàn Piano đứng</h5>
                            <p>Thanh lịch cho không gian sống hiện đại.</p>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('product.index', ['category' => '3']) }}" class="category-link">
                        <div class="category-box">
                            <i class="fa-solid fa-headphones category-icon"></i>
                            <h5>Đàn Piano điện</h5>
                            <p>Công nghệ dẫn lối âm nhạc.</p>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('product.index', ['category' => '4']) }}" class="category-link">
                        <div class="category-box">
                            <i class="fa-solid fa-sliders category-icon"></i>
                            <h5>Phụ kiện Piano</h5>
                            <p>Chi tiết nhỏ, trải nghiệm lớn.</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>
        <section class="mt-5">
            <h2 class="section-title">
                Sản phẩm mới
            </h2>
            <div class="row g-4">
                @foreach($latestProducts as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card product-card h-100">
                            @if($product->primaryImage)
                                <img src="{{ asset($product->primaryImage->image_path) }}"
                                     class="product-img" loading="lazy">
                            @endif
                            <div class="card-body">
                                <h6 class="text-muted">
                                    {{ $product->brand->name }}
                                </h6>
                                <h5 class="product-title">
                                    <a href="{{ route('product.show',$product->slug) }}">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                <p>
                                    @if($product->sale_price)
                                        <span class="price-sale">
                                            {{ number_format($product->sale_price,0,',','.') }}
                                        </span>
                                        <del class="price-old">
                                            {{ number_format($product->price,0,',','.') }}
                                        </del>
                                    @else
                                        <span class="price">
                                            {{ number_format($product->price,0,',','.') }}
                                        </span>
                                    @endif
                                </p>
                                <a href="{{ route('product.show',$product->slug) }}"
                                   class="btn btn-outline-primary w-100">
                                    Xem chi tiết
                                </a>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <section class="mt-5">
            <h2 class="section-title">
                Các chương trình giảm giá
            </h2>

            <div class="row g-4">
                @forelse($activePromotions as $promotion)
                    @php
                        $myCoupon = Auth::check() && $promotion->relationLoaded('coupons')
                            ? $promotion->coupons->first()
                            : null;
                    @endphp

                    <div class="col-md-4 mb-4">
                        <div class="card promotion-card h-100">
                            @if($promotion->starts_at >= now())
                                <div class="promotion-badge">
                                    Sắp tới
                                </div>
                            @else
                                <div class="promotion-badge">
                                    Đang áp dụng
                                </div>
                            @endif

                            <div class="card-body">
                                <h5 class="promotion-title">
                                    {{ $promotion->name }}
                                </h5>

                                <div class="promotion-value">
                                    @if($promotion->discount_type === 'percent')
                                        Giảm {{ $promotion->discount_value }}%
                                    @else
                                        Giảm {{ number_format($promotion->discount_value, 0, ',', '.') }}đ
                                    @endif
                                </div>

                                <p class="promotion-note">
                                    Áp dụng cho đơn từ
                                    <strong>{{ number_format($promotion->min_order_amount, 0, ',', '.') }}đ</strong>
                                </p>

                                @if($promotion->max_discount_amount)
                                    <p class="promotion-note">
                                        Giảm tối đa
                                        <strong>{{ number_format($promotion->max_discount_amount, 0, ',', '.') }}đ</strong>
                                    </p>
                                @endif

                                @if($promotion->starts_at >= now())
                                    <p class="promotion-expire">
                                        Bắt đầu từ: {{ $promotion->starts_at->format('d/m/Y H:i') }}
                                    </p>
                                @endif

                                @if($promotion->ends_at)
                                    <p class="promotion-expire">
                                        Hết hạn: {{ $promotion->ends_at->format('d/m/Y H:i') }}
                                    </p>
                                @endif

                                @auth
                                    @if($myCoupon)
                                        <div class="promotion-code">
                                            Mã của bạn:
                                            <span>{{ $myCoupon->code }}</span>
                                        </div>
                                        <a class="btn btn-outline-primary w-100 mt-3" href="{{ route('product.index') }}">Mua sắm ngay</a>
                                    @else
                                        <div class="promotion-code muted">
                                            Bạn chưa có mã khả dụng cho chương trình này
                                        </div>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mt-3">
                                        Đăng nhập để nhận mã
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-5 bg-white rounded shadow-sm">
                            Hiện chưa có chương trình giảm giá nào.
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

    </div>

    @guest
        <div class="account-modal-backdrop" id="accountPopup">
            <div class="account-modal">
                <button type="button" class="account-modal-close" id="accountPopupClose" aria-label="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="account-modal-icon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <span class="account-modal-label">Thành viên KEY SPACE</span>
                <h3>Đăng nhập để trải nghiệm tốt hơn</h3>
                <p>
                    Lưu mẫu đàn piano yêu thích, theo dõi đơn hàng và nhận tư vấn nhanh hơn từ KEY SPACE.
                </p>
                <div class="account-modal-actions">
                    <a href="{{ route('login') }}" class="account-modal-login">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="account-modal-register">Đăng ký tài khoản</a>
                </div>
            </div>
        </div>
    @endguest



    <style>
        .account-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(16, 13, 9, .58);
            opacity: 0;
            visibility: hidden;
            transition: .25s ease;
        }

        .account-modal-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        .account-modal {
            position: relative;
            width: 100%;
            max-width: 460px;
            padding: 38px 34px 32px;
            text-align: center;
            background: #fffdf8;
            border: 1px solid rgba(201, 164, 92, .42);
            border-radius: 14px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .35);
            transform: translateY(18px) scale(.96);
            transition: .25s ease;
        }

        .account-modal-backdrop.show .account-modal {
            transform: translateY(0) scale(1);
        }

        .account-modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 50%;
            background: #f1eadc;
            color: #5f5548;
        }

        .account-modal-close:hover {
            background: #1d1a16;
            color: #fffaf0;
        }

        .account-modal-icon {
            width: 62px;
            height: 62px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: #1d1a16;
            color: #c9a45c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .account-modal-label {
            color: #9c7432;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .account-modal h3 {
            margin: 10px 0 12px;
            color: #181510;
            font-size: 28px;
            font-weight: 800;
        }

        .account-modal p {
            color: #746b5d;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .account-modal-actions {
            display: flex;
            gap: 12px;
        }

        .account-modal-login,
        .account-modal-register {
            flex: 1;
            padding: 12px 18px;
            border-radius: 999px;
            font-weight: 800;
            text-decoration: none;
        }

        .account-modal-login {
            color: #1d1a16;
            border: 1px solid #d7c7a8;
        }

        .account-modal-login:hover {
            border-color: #1d1a16;
            color: #1d1a16;
        }

        .account-modal-register {
            color: #17130d;
            background: #c9a45c;
            border: 1px solid #c9a45c;
        }

        .account-modal-register:hover {
            background: #b68e42;
            color: #17130d;
        }

        @media (max-width: 576px) {
            .account-modal {
                padding: 34px 22px 26px;
            }

            .account-modal-actions {
                flex-direction: column;
            }
        }

        .hero {
            position: relative;
            height: 560px;
            width: 100%;
            overflow: hidden;
            margin-bottom: 24px;
            background: #111;
            box-shadow:0 15px 30px rgba(0,0,0,.3);
        }

        .hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.05);
            transition:1.2s;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(9, 8, 6, .88), rgba(20, 17, 12, .58), rgba(20, 17, 12, .08)),
                linear-gradient(0deg, rgba(0, 0, 0, .35), transparent 45%);
        }

        .hero-content {
            position: absolute;
            left: 10%;
            top: 20%;
            color: #fffaf0;
            max-width: 620px;
        }

        .hero-kicker,
        .section-title::before {
            display: block;
            color: #c9a45c;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .hero h1 {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 64px;
            line-height: 1.05;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 20px;
            line-height: 1.7;
            color: rgba(255, 250, 240, .86);
            margin-bottom: 32px;
        }

        .btn-luxury {
            background: #c9a45c;
            border-color: #c9a45c;
            color: #15120c;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 999px;
        }

        .btn-luxury:hover {
            background: #b68e42;
            border-color: #b68e42;
            color: #15120c;
        }

        .section-title {
            margin: 46px 0 26px;
            color: #181510;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 34px;
            font-weight: 700;
        }

        .category-box {
            min-height: 170px;
            background: #fffdf7;
            padding: 28px 22px;
            border: 1px solid rgba(201, 164, 92, .28);
            border-radius: 10px;
            box-shadow: 0 14px 36px rgba(24, 21, 16, .08);
            transition: .25s ease;
        }

        .category-box:hover {
            transform: translateY(-6px);
            border-color: rgba(201, 164, 92, .7);
            box-shadow: 0 20px 44px rgba(24, 21, 16, .14);
        }

        .category-icon {
            color: #c9a45c;
            font-size: 30px;
            margin-bottom: 18px;
        }

        .category-box h5 {
            font-weight: 700;
            color: #181510;
        }

        .category-box p {
            color: #746b5d;
            font-size: 14px;
            margin-bottom: 0;
        }

        .category-link{
            text-decoration:none;
            color:inherit;
            display:block;
        }

        .product-card {
            border: 1px solid rgba(24, 21, 16, .08);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 12px 34px rgba(24, 21, 16, .08);
            transition: .25s ease;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 46px rgba(24, 21, 16, .16);
        }

        .product-img {
            width: 100%;
            height: 230px;
            object-fit: cover;
            background: #f5efe2;
        }

        .product-title {
            min-height: 52px;
            overflow: hidden;
            font-weight: 700;
        }

        .product-title a {
            color: #181510;
            text-decoration: none;
        }

        .product-title a:hover {
            color: #9c7432;
        }

        .price,
        .price-sale {
            color: #9c7432;
            font-size: 20px;
            font-weight: 800;
        }

        .price-old {
            color: #9a9388;
            margin-left: 8px;
        }

        .sale-tag {
            position: absolute;
            right: 12px;
            top: 12px;
            background: #7f1d1d;
            color: #fffaf0;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .btn-outline-primary {
            color: #9c7432;
            border-color: #c9a45c;
            border-radius: 999px;
            font-weight: 700;
        }

        .btn-outline-primary:hover {
            background: #c9a45c;
            border-color: #c9a45c;
            color: #15120c;
        }


        .promotion-card {
            position: relative;
            border: 1px solid rgba(201, 164, 92, .32);
            border-radius: 10px;
            overflow: hidden;
            background: #fffdf7;
            box-shadow: 0 12px 34px rgba(24, 21, 16, .08);
        }

        .promotion-badge {
            position: absolute;
            right: 14px;
            top: 14px;
            background: #ac2d2d;
            color: #fffaf0;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .promotion-title {
            padding-right: 82px;
            color: #181510;
            font-weight: 800;
            min-height: 54px;
            max-width:80%;
        }

        .promotion-value {
            color: #9c7432;
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 12px;
        }

        .promotion-note,
        .promotion-expire {
            color: #746b5d;
            margin-bottom: 8px;
        }

        .promotion-code {
            margin-top: 16px;
            padding: 12px;
            border: 1px dashed #c9a45c;
            border-radius: 8px;
            background: #fff8e8;
            font-weight: 700;
        }

        .promotion-code span {
            display: block;
            color: #7f1d1d;
            font-size: 18px;
            margin-top: 4px;
        }

        .promotion-code.muted {
            color: #8a8173;
            border-color: #ddd1bb;
            background: #f8f3ea;
        }

        @media (max-width: 768px) {
            .hero {
                height: 480px;
            }

            .hero-content {
                left: 24px;
                right: 24px;
            }

            .hero h1 {
                font-size: 44px;
            }
        }
    </style>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const popup = document.getElementById('accountPopup');
            const closeButton = document.getElementById('accountPopupClose');

            if (!popup || localStorage.getItem('keyspace_account_popup_closed') === '1') {
                return;
            }

            setTimeout(function () {
                popup.classList.add('show');
            }, 1200);

            closeButton.addEventListener('click', function () {
                popup.classList.remove('show');
                localStorage.setItem('keyspace_account_popup_closed', '1');
            });

            popup.addEventListener('click', function (event) {
                if (event.target === popup) {
                    popup.classList.remove('show');
                    localStorage.setItem('keyspace_account_popup_closed', '1');
                }
            });
        });
    </script>
@endpush
