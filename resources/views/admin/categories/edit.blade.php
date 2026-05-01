@extends('admin.layouts.master')
@section('title', 'Sửa danh mục đàn')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0">Chỉnh sửa Danh mục</h4>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                {{-- Nhớ trỏ action về update và truyền ID --}}
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Bắt buộc phải có để báo Laravel đây là form Cập nhật --}}

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-save"></i> Cập nhật
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
