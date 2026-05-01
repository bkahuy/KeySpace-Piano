@extends('admin.layouts.master')
@section('title', 'Danh sách Đàn Piano')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-list me-2"></i>Quản lý Đàn Piano</h5>

            {{-- Nút Thêm mới sản phẩm --}}
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus-circle me-1"></i> Thêm sản phẩm mới
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>SKU</th>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th class="text-end">Giá bán</th>
                        <th class="text-center">Kho</th>
                        <th class="text-center">Tình trạng</th>
                        <th class="text-center" style="width: 120px;">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $startCount = ($products->currentPage() - 1) * $products->perPage() + 1; @endphp
                    @foreach($products as $product)
                        <tr>
                            <td class="text-center text-muted">{{ $startCount++ }}</td>
                            <td class="fw-bold">{{ $product->sku }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark fw-bold">
                                        {{ $product->name }}
                                    </a>
                                </div>
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->brand->name }}</td>
                            <td class="text-end fw-bold text-danger">
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            </td>
                            <td class="text-center">
                                @if($product->stock_quantity > 0)
                                    <span class="badge bg-success text-white rounded-pill px-3">{{ $product->stock_quantity }}</span>
                                @else
                                    <span class="badge bg-danger text-white rounded-pill px-3">Hết hàng</span>
                                @endif
                            </td>
                            <td class="text-center">
                            <span class="badge bg-{{ $product->condition == 'new' ? 'primary' : 'secondary' }} rounded-pill">
                                {{ strtoupper($product->condition) }}
                            </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    {{-- NÚT SỬA: Chuyển hướng sang trang Edit --}}
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline-block form-delete-product">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-product" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Hiển thị thanh Phân trang --}}
        <div class="card-footer bg-white pt-4 pb-3">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-delete-product').forEach(button => {
            button.addEventListener('click', function() {
                let form = this.closest('.form-delete-product');

                Swal.fire({
                    title: 'Xác nhận xóa sản phẩm',
                    html: "Bạn có chắc chắn muốn xóa đàn này không?<br><span style='font-size: 0.9em; color: #6c757d;'>Hành động này sẽ xóa toàn bộ hình ảnh trong ổ cứng và không thể khôi phục!</span>",
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
