@extends('admin.layouts.master')
@section('title', 'Thêm danh mục đàn')


@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0">Thêm Danh mục mới</h4>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="VD: Grand Piano..." required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i> Lưu danh mục
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
