@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="m-0">Quản lý Khách hàng</h4>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fa-solid fa-users"></i> Danh sách Tài khoản
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Họ và Tên</th>
                            <th width="25%">Email</th>
                            <th width="15%">Điện thoại</th>
                            <th width="15%">Ngày tham gia</th>
                            <th width="10%">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($customers as $key => $customer)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-bold">{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone ?? 'Chưa cập nhật' }}</td>
                                <td>{{ $customer->created_at ? $customer->created_at->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    {{-- Nút Xem chi tiết (Thay cho nút Xóa cũ) --}}
                                    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết & Lịch sử đơn hàng">
                                        <i class="fa-solid fa-eye"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Chưa có khách hàng nào đăng ký.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div class="mt-3">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
