@extends('admin.layouts.master')
@section('title', 'Sửa sản phẩm: ' . $product->name)

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Sửa sản phẩm: {{ $product->name }}</h5>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM SỬA: Phải có @method('PUT') --}}
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mã SKU <span class="text-danger">*</span></label>
                                <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Thương hiệu <span class="text-danger">*</span></label>
                                <select name="brand_id" class="form-select" required>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Giá bán gốc (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Giá khuyến mãi</label>
                                <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tồn kho <span class="text-danger">*</span></label>
                                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tình trạng</label>
                                <select name="condition" class="form-select" required>
                                    <option value="new" {{ old('condition', $product->condition) == 'new' ? 'selected' : '' }}>Mới (New)</option>
                                    <option value="used" {{ old('condition', $product->condition) == 'used' ? 'selected' : '' }}>Cũ (Used)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Màu sắc</label>
                                <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Bảo hành (Tháng)</label>
                                <input type="number" name="warranty_period" class="form-control" value="{{ old('warranty_period', $product->warranty_period) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả ngắn <span class="text-danger">*</span></label>
                            <textarea name="short_description" class="form-control" rows="3" required>{{ old('short_description', $product->short_description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea name="detailed_description" class="form-control" rows="6">{{ old('detailed_description', $product->detailed_description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="fa-solid fa-images me-2"></i>Cập nhật Hình Ảnh</h6>

                                <div class="alert alert-info py-2 fs-6">
                                    <i class="fa-solid fa-circle-info me-1"></i> Nếu bạn không chọn ảnh mới, hệ thống sẽ giữ nguyên bộ ảnh cũ. Nếu chọn, bộ ảnh cũ sẽ bị xóa.
                                </div>

                                <div class="mb-3">
                                    <input type="file" name="images[]" id="imageInput" class="form-control" multiple accept="image/*">
                                </div>

                                <div id="imagePreview" class="row g-2 mt-3">
                                    {{-- HIỂN THỊ ẢNH CŨ TỪ DATABASE LÊN ĐÂY TRƯỚC --}}
                                    @foreach($product->images as $img)
                                        <div class="col-6 col-md-4">
                                            <div class="card thumbnail-card h-100 {{ $img->is_primary ? 'border-primary shadow-sm' : '' }}">
                                                @if($img->is_primary)
                                                    <span class="badge bg-primary position-absolute" style="top:5px; right:5px;">Chính</span>
                                                @endif
                                                <img src="{{ asset($img->image_path) }}" class="card-img-top p-1" style="height: 100px; object-fit: cover;">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <input type="hidden" name="primary_image_index" id="primaryImageIndex" value="0">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="mt-4 mb-4">
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold">
                        <i class="fa-solid fa-save me-2"></i> LƯU THAY ĐỔI
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .thumbnail-card { position: relative; border: 2px solid #dee2e6; transition: 0.2s; }
        .thumbnail-card.is-primary { border-color: #0d6efd; box-shadow: 0 0 10px rgba(13, 110, 253, 0.5); }
        .thumbnail-card .primary-badge { position: absolute; top: 5px; right: 5px; display: none; }
        .thumbnail-card.is-primary .primary-badge { display: block; }
    </style>

    @push('scripts')
        <script>
            const imageInput = document.getElementById('imageInput');
            const imagePreviewArea = document.getElementById('imagePreview');
            const primaryIndexInput = document.getElementById('primaryImageIndex');

            // Chạy y hệt như trang Create để preview ảnh mới
            imageInput.addEventListener('change', function() {
                imagePreviewArea.innerHTML = '';
                primaryIndexInput.value = '0';
                const files = this.files;

                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-6 col-md-4';

                            const card = document.createElement('div');
                            card.className = `card thumbnail-card h-100 ${i === 0 ? 'is-primary' : ''}`;
                            card.setAttribute('data-index', i);
                            card.style.cursor = 'pointer';

                            const badge = document.createElement('span');
                            badge.className = 'badge bg-primary position-absolute primary-badge';
                            badge.innerHTML = 'Chính';
                            card.appendChild(badge);

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'card-img-top p-1';
                            img.style.height = '100px';
                            img.style.objectFit = 'cover';
                            card.appendChild(img);

                            col.appendChild(card);
                            imagePreviewArea.appendChild(col);

                            card.addEventListener('click', function() {
                                const allCards = document.querySelectorAll('.thumbnail-card');
                                allCards.forEach(c => c.classList.remove('is-primary'));
                                this.classList.add('is-primary');
                                primaryIndexInput.value = this.getAttribute('data-index');
                            });
                        }
                        reader.readAsDataURL(file);
                    }
                }
            });
        </script>
    @endpush
@endsection
