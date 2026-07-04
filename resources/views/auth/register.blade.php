{{-- resources/views/auth/register.blade.php --}}
@extends('layout.auth')

@section('title', 'Đăng ký')
@section('auth_heading', 'Tạo tài khoản KEY SPACE')
@section('auth_description', 'Lưu mẫu đàn bạn quan tâm, nhận báo giá nhanh và theo dõi quá trình mua đàn dễ dàng hơn.')

@section('content')
    <h2>
        <i class="fa-solid fa-user-plus me-2 text-gold"></i>
        Tạo tài khoản
    </h2>

    <p class="subtitle">Tạo tài khoản để mua đàn và nhận hỗ trợ nhanh hơn.</p>

    <form method="POST" action="{{ route('register.submit') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nhập họ và tên" value="{{ old('name') }}" >
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Ví dụ: khachhang@gmail.com" value="{{ old('email') }}" required>
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Ví dụ: 0987 654 321" value="{{ old('phone') }}" required>
            @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>

            <div class="input-group">
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Tối thiểu 6 ký tự"
                    required
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


        <div class="mb-4">
            <label class="form-label">Nhập lại mật khẩu</label>

            <div class="input-group">
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    placeholder="Nhập lại mật khẩu"
                    required
                >

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-toggle-password="password_confirmation"
                    aria-label="Ẩn hiện mật khẩu nhập lại"
                >
                    <i class="fa-solid fa-eye-slash"></i>
                </button>

                @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>


        </div>



        <button type="submit" class="btn w-100 btn-auth">
            Đăng ký
        </button>

        <p class="text-center mt-4 mb-0">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="auth-link">Đăng nhập</a>
        </p>
    </form>
@endsection
