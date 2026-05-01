@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0">Quản lý Đánh giá Sản phẩm</h4>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fa-solid fa-star text-warning"></i> Danh sách Đánh giá
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Khách hàng</th>
                            <th width="20%">Sản phẩm</th>
                            <th width="10%">Đánh giá</th>
                            <th width="30%">Nội dung bình luận</th>
                            <th width="10%">Trạng thái</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($reviews as $key => $review)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-bold">{{ $review->user->name ?? 'User đã xóa' }}</td>
                                <td>
                                    @if($review->product)
                                        <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" class="text-decoration-none">
                                            {{ Str::limit($review->product->name, 30) }}
                                        </a>
                                    @else
                                        <span class="text-danger">Sản phẩm đã xóa</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-warning">
                                        {{ $review->rating }} <i class="fa-solid fa-star"></i>
                                    </div>
                                </td>
                                <td>
                                    {{ Str::limit($review->comment, 60) }}
                                    <br>
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    {{-- Nút Toggle Ẩn/Hiện --}}
                                    <form action="{{ route('reviews.toggleStatus', $review->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm w-100 fw-bold {{ $review->is_approved ? 'btn-success' : 'btn-secondary' }}">
                                            {{ $review->is_approved ? 'Đang hiện' : 'Đã ẩn' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    {{-- Nút Xóa (Đã gọi sẵn class của SweetAlert2) --}}
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline-block form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete" title="Xóa đánh giá">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Chưa có đánh giá nào.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div class="mt-3">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>


    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('.form-delete');

                Swal.fire({
                    title: 'Xác nhận xóa đánh giá',
                    html: "Bạn có chắc chắn muốn xóa đánh giá này không?<br><span style='font-size: 0.9em; color: #6c757d;'>Hành động này không thể khôi phục!</span>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#f8f9fa',
                    confirmButtonText: 'Vâng, Xóa!',
                    cancelButtonText: '<span style="color:black">Hủy</span>'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
