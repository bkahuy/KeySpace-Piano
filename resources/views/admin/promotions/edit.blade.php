@extends('admin.layouts.master')
@section('title', 'Sửa khuyến mãi')

@section('content')
    <form action="{{ route('promotions.update', $promotion->id) }}" method="POST" class="card border-0 shadow-sm">
        @csrf
        @method('PUT')

        <div class="card-body">
            @include('admin.promotions.form', ['promotion' => $promotion])

            <button class="btn btn-primary">
                Cập nhật
            </button>

            <a href="{{ route('promotions.index') }}" class="btn btn-secondary">
                Quay lại
            </a>
        </div>
    </form>
@endsection
