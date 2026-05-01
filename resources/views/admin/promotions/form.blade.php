@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label class="form-label fw-bold">Tên chương trình</label>
    <input type="text"
           name="name"
           class="form-control"
           value="{{ old('name', $promotion?->name) }}"
           required>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">Tiền tố mã</label>
    <input type="text"
           name="code_prefix"
           class="form-control"
           value="{{ old('code_prefix', $promotion?->code_prefix) }}"
           placeholder="VD: SUMMER2026"
           required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Loại giảm giá</label>
        <select name="discount_type" class="form-select" required>
            <option value="percent" {{ old('discount_type', $promotion?->discount_type) === 'percent' ? 'selected' : '' }}>
                Giảm theo phần trăm
            </option>
            <option value="fixed" {{ old('discount_type', $promotion?->discount_type) === 'fixed' ? 'selected' : '' }}>
                Giảm theo số tiền
            </option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Giá trị giảm</label>
        <input type="number"
               name="discount_value"
               class="form-control"
               min="1"
               value="{{ old('discount_value', $promotion?->discount_value) }}"
               required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Đơn hàng tối thiểu</label>
        <input type="number"
               name="min_order_amount"
               class="form-control"
               min="0"
               value="{{ old('min_order_amount', $promotion?->min_order_amount ?? 0) }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Giảm tối đa</label>
        <input type="number"
               name="max_discount_amount"
               class="form-control"
               min="0"
               value="{{ old('max_discount_amount', $promotion?->max_discount_amount) }}">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Ngày bắt đầu</label>
        <input type="datetime-local"
               name="starts_at"
               class="form-control"
               value="{{ old('starts_at', $promotion?->starts_at ? $promotion->starts_at->format('Y-m-d\TH:i') : '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Ngày kết thúc</label>
        <input type="datetime-local"
               name="ends_at"
               class="form-control"
               value="{{ old('ends_at', $promotion?->ends_at ? $promotion->ends_at->format('Y-m-d\TH:i') : '') }}">
    </div>
</div>

<div class="form-check mb-4">
    <input type="checkbox"
           name="is_active"
           value="1"
           class="form-check-input"
           id="is_active"
        {{ old('is_active', $promotion?->is_active ?? true) ? 'checked' : '' }}>

    <label class="form-check-label fw-bold" for="is_active">
        Kích hoạt chương trình
    </label>
</div>
