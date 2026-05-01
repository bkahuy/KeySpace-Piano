@extends('admin.layouts.master')
@section('title', 'Quản lý danh mục đàn')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0">Danh sách Danh mục</h4>
            {{-- Nút Thêm mới (Tạm thời href để dấu #) --}}
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Thêm danh mục mới
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fa-solid fa-list"></i> Quản lý Danh mục
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Tên danh mục</th>
                            <th width="20%">Số lượng SP</th>
                            <th width="15%">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($categories as $key => $category)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-bold">{{ $category->name }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $category->products->count() ?? 0 }} Sản phẩm
                                    </span>
                                </td>
                                <td>
                                    {{-- Nút Sửa --}}
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline-block form-delete-category">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-category">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Chưa có danh mục nào.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div class="card-footer bg-white pt-4 pb-3">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.btn-delete-category').forEach(button => {
            button.addEventListener('click', function() {
                let form = this.closest('.form-delete-category');

                Swal.fire({
                    title: 'Xác nhận xóa danh mục',
                    html: "Bạn có chắc chắn muốn xóa danh mục này không?<br><span style='font-size: 0.9em; color: #6c757d;'>Hành động này có thể ảnh hưởng đến các sản phẩm liên quan!</span>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#f8f9fa',
                    confirmButtonText: 'Vâng, Xóa ngay!',
                    cancelButtonText: '<span style="color: black">Hủy bỏ</span>'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
