@extends('layout.auth')

@section('title', 'Đăng nhập hệ thống')

@section('auth_heading', 'Chào mừng trở lại KEY SPACE')

@section('auth_description')
    Đăng nhập để theo dõi đơn hàng, lưu mẫu đàn piano yêu thích và nhận hỗ trợ nhanh hơn từ KEY SPACE.
@endsection

@section('content')
    <h2>
        <i class="fa-solid fa-user-lock me-2 text-gold"></i>
        Đăng nhập
    </h2>

    <p class="subtitle">
        Nhập thông tin tài khoản để tiếp tục sử dụng hệ thống.
    </p>

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Địa chỉ Email</label>
            <input
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                autofocus
                placeholder="Ví dụ: buihuy@gmail.com"
            >
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Mật khẩu</label>

            <div class="input-group">
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"

                    placeholder="Nhập mật khẩu..."
                >

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-toggle-password="password"
                    aria-label="Ẩn hiện mật khẩu"
                >
                    <i class="fa-solid fa-eye-slash"></i>
                </button>

                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>


        <button type="submit" class="btn w-100 btn-auth">
            Đăng nhập
        </button>
    </form>
    <p class="text-center mt-4 mb-0">
        Chưa có tài khoản?
        <a href="{{ route('register') }}" class="auth-link">Tạo tài khoản</a>
    </p>

@endsection
