@extends('admin.layouts.master')
@section('title', 'Thêm đàn Piano mới')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-plus-circle me-2"></i>Thêm sản phẩm Đàn Piano mới</h5>
        </div>

        <div class="card-body">

            {{-- Hiển thị thông báo lỗi Validate nếu có --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- BẮT ĐẦU FORM (Lưu ý: Phần upload ảnh bắt buộc phải có thuộc tính: enctype="multipart/form-data") --}}
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- CỘT TRÁI: Thông tin chữ --}}
                    <div class="col-md-8">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ví dụ: Yamaha U3H" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mã SKU <span class="text-danger">*</span></label>
                                <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" placeholder="Ví dụ: YAM-U3H-001" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="" selected disabled>-- Chọn danh mục --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Thương hiệu <span class="text-danger">*</span></label>
                                <select name="brand_id" class="form-select" required>
                                    <option value="" selected disabled>-- Chọn thương hiệu --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Giá bán gốc (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Giá khuyến mãi (VNĐ)</label>
                                <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price') }}" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tồn kho <span class="text-danger">*</span></label>
                                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}" required min="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tình trạng <span class="text-danger">*</span></label>
                                <select name="condition" class="form-select" required>
                                    <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>Mới (New)</option>
                                    <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Cũ (Used)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Màu sắc</label>
                                <input type="text" name="color" class="form-control" value="{{ old('color') }}" placeholder="Đen bóng, Vân gỗ...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Bảo hành (Tháng) <span class="text-danger">*</span></label>
                                <input type="number" name="warranty_period" class="form-control" value="{{ old('warranty_period') }}" required min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả ngắn <span class="text-danger">*</span></label>
                            <textarea name="short_description" class="form-control" rows="3" required>{{ old('short_description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            {{-- (Mẹo: Bạn nên tích hợp CKEditor/TinyMCE vào thẻ textarea này để Admin nhập mô tả định dạng đẹp) --}}
                            <textarea name="detailed_description" class="form-control" rows="8">{{ old('detailed_description') }}</textarea>
                        </div>

                    </div>

                    {{-- CỘT PHẢI: Upload Ảnh --}}
                    <div class="col-md-4">
                        <div class="card bg-light border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="fa-solid fa-images me-2"></i>Upload Hình Ảnh <span class="text-danger">*</span></h6>

                                {{-- Input chọn nhiều file ảnh --}}
                                <div class="mb-3">
                                    <label class="form-label text-muted">Chọn 1 hoặc nhiều ảnh (jpeg, png, jpg, gif - tối đa 50MB/ảnh)</label>
                                    <input type="file" name="images[]" id="imageInput" class="form-control" multiple accept="image/*" required>
                                </div>

                                {{-- Khu vực hiển thị ảnh xem trước (Preview Area) --}}
                                <div id="imagePreview" class="row g-2">
                                    <div class="col-12 text-center text-muted py-4 border border-dashed rounded bg-white">
                                        <i class="fa-regular fa-image" style="font-size: 40px;"></i>
                                        <p class="mt-2 mb-0">Ảnh xem trước sẽ hiện ở đây</p>
                                    </div>
                                </div>

                                {{-- Input ẩn để lưu chỉ số của ảnh chính --}}
                                {{-- Giá trị mặc định là 0 (ảnh đầu tiên)</dd --}}
                                <input type="hidden" name="primary_image_index" id="primaryImageIndex" value="0">

                            </div>
                        </div>
                    </div>
                </div>

                <hr class="mt-4 mb-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg px-4">Hủy bỏ</a>
                    <button type="submit" class="btn btn-danger btn-lg px-5fw-bold">
                        <i class="fa-solid fa-save me-2"></i> LƯU SẢN PHẨM
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
    {{-- CSS tùy chỉnh cho phần Preview ảnh --}}
    <style>
        .border-dashed { border: 2px dashed #adb5bd !important; }
        .thumbnail-card { position: relative; border: 2px solid #dee2e6; cursor: pointer; transition: 0.2s; }
        .thumbnail-card:hover { border-color: #6c757d; }
        /* CSS cho ảnh được chọn làm ảnh chính */
        .thumbnail-card.is-primary { border-color: #0d6efd; box-shadow: 0 0 10px rgba(13, 110, 253, 0.5); }
        .thumbnail-card .primary-badge { position: absolute; top: 5px; right: 5px; display: none; }
        .thumbnail-card.is-primary .primary-badge { display: block; }
    </style>

    {{-- JAVASCRIPT XỬ LÝ PREVIEW VÀ CHỌN ẢNH CHÍNH --}}
    @push('scripts')
    <script>
        const imageInput = document.getElementById('imageInput');
        const imagePreviewArea = document.getElementById('imagePreview');
        const primaryIndexInput = document.getElementById('primaryImageIndex');

        imageInput.addEventListener('change', function() {
            // Xóa sạch preview cũ
            imagePreviewArea.innerHTML = '';
            // Reset chỉ số ảnh chính về 0
            primaryIndexInput.value = '0';

            const files = this.files;

            if (files.length > 0) {
                // Lặp qua từng file để tạo preview
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-6 col-md-4';

                        // Tạo khung chứa ảnh (có thể click được)
                        const card = document.createElement('div');
                        // Gán class 'is-primary' cho ảnh đầu tiên (i===0)
                        card.className = `card thumbnail-card h-100 ${i === 0 ? 'is-primary' : ''}`;
                        card.setAttribute('data-index', i); // Lưu chỉ số để lấy sau này

                        // Tạo badge "Ảnh chính"
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-primary position-absolute primary-badge';
                        badge.innerHTML = '<i class="fa-solid fa-check-circle me-1"></i>Chính';
                        card.appendChild(badge);

                        // Tạo thẻ img
                        const img = document.createElement('img');
                        img.src = e.target.result; // Dữ liệu ảnh dạng base64
                        img.className = 'card-img-top p-1';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        card.appendChild(img);

                        col.appendChild(card);
                        imagePreviewArea.appendChild(col);

                        // --- LẮP SỰ KIỆN CLICK ĐỂ CHỌN ẢNH CHÍNH ---
                        card.addEventListener('click', function() {
                            // 1. Xóa class 'is-primary' của tất cả các ảnh khác
                            const allCards = document.querySelectorAll('.thumbnail-card');
                            allCards.forEach(c => c.classList.remove('is-primary'));

                            // 2. Thêm class 'is-primary' cho ảnh vừa click
                            this.classList.add('is-primary');

                            // 3. Cập nhật chỉ số (data-index) vào input ẩn
                            primaryIndexInput.value = this.getAttribute('data-index');
                        });
                    }

                    // Đọc file dưới dạng URL
                    reader.readAsDataURL(file);
                }
            } else {
                // Nếu không chọn file nào, hiện lại thông báo trống
                imagePreviewArea.innerHTML = `
                <div class="col-12 text-center text-muted py-4 border border-dashed rounded bg-white">
                    <i class="fa-regular fa-image" style="font-size: 40px;"></i>
                    <p class="mt-2 mb-0">Ảnh xem trước sẽ hiện ở đây</p>
                </div>
            `;
            }
        });
    </script>
    @endpush
