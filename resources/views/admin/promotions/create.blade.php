@extends('admin.layouts.master')
@section('title', 'Thêm khuyến mãi')

@section('content')
    <form action="{{ route('promotions.store') }}" method="POST" class="card border-0 shadow-sm">
        @csrf

        <div class="card-body">
            @include('admin.promotions.form', ['promotion' => null])

            <button class="btn btn-primary">
                Lưu chương trình
            </button>

            <a href="{{ route('promotions.index') }}" class="btn btn-secondary">
                Quay lại
            </a>
        </div>
    </form>
@endsection
