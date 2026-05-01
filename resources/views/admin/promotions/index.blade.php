@extends('admin.layouts.master')
@section('title', 'Quản lý khuyến mãi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Chương trình khuyến mãi</h4>

        <a href="{{ route('promotions.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Thêm khuyến mãi
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                <tr>
                    <th>Tên chương trình</th>
                    <th>Tiền tố mã</th>
                    <th>Giảm giá</th>
                    <th>Thời gian</th>
                    <th>Mã đã phát</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse($promotions as $promotion)
                    @php
                        $isRunning = $promotion->isValidNow();
                    @endphp

                    <tr>
                        <td class="fw-bold">{{ $promotion->name }}</td>
                        <td>{{ $promotion->code_prefix }}</td>
                        <td>
                            @if($promotion->discount_type === 'percent')
                                {{ $promotion->discount_value }}%
                            @else
                                {{ number_format($promotion->discount_value, 0, ',', '.') }}đ
                            @endif
                        </td>
                        <td>
                            <div>Bắt đầu: {{ $promotion->starts_at ? $promotion->starts_at->format('d/m/Y H:i') : 'Không giới hạn' }}</div>
                            <div>Kết thúc: {{ $promotion->ends_at ? $promotion->ends_at->format('d/m/Y H:i') : 'Không giới hạn' }}</div>
                        </td>
                        <td>
                            {{ $promotion->used_coupons_count }}/{{ $promotion->coupons_count }} đã dùng
                        </td>
                        <td>
                            @if(!$promotion->is_active)
                                <span class="badge bg-secondary">Đã tắt</span>
                            @elseif($isRunning)
                                <span class="badge bg-success">Đang chạy</span>
                            @elseif($promotion->starts_at && now()->lt($promotion->starts_at))
                                <span class="badge bg-info text-dark">Sắp diễn ra</span>
                            @else
                                <span class="badge bg-danger">Đã hết hạn</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('promotions.edit', $promotion->id) }}" class="btn btn-sm btn-outline-primary">
                                Sửa
                            </a>

                            <form action="{{ route('promotions.destroy', $promotion->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa chương trình này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Chưa có chương trình khuyến mãi.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $promotions->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
