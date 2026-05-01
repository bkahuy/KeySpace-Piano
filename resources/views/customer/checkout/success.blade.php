@extends('layout.index')
@section('title', 'Đặt hàng thành công')

@section('content')
    <div class="container py-5 text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <i class="fa-solid fa-circle-check text-success" style="font-size: 100px;"></i>
                <h2 class="fw-bold mt-4">Đặt hàng thành công!</h2>
                <p class="fs-5 text-muted mt-3">Cảm ơn bạn đã mua sắm tại Piano Store.</p>

                <div class="alert alert-info mt-4 text-start">
                    <p class="mb-1">Mã đơn hàng của bạn là: <strong class="text-danger fs-5">{{ session('orderCode') }}</strong></p>
                    <p class="mb-0">Chúng tôi sẽ sớm liên hệ với bạn để xác nhận đơn hàng và tiến hành giao hàng.</p>
                </div>

                <a href="{{ route('home') }}" class="btn btn-primary mt-4 px-5 py-2">Tiếp tục mua sắm</a>
            </div>
        </div>
    </div>
@endsection
