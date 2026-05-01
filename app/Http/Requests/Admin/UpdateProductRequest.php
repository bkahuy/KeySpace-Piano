<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Lấy ID của sản phẩm đang được sửa từ URL
        $productId = $this->route('product');

        return [
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            // Dòng này báo cho Laravel: "Mã SKU phải duy nhất, NHƯNG ngoại trừ chính cái sản phẩm có ID này"
            'sku' => 'required|string|max:100|unique:products,sku,' . $productId,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'condition' => 'required|in:new,used',
            'color' => 'nullable|string|max:100',
            'warranty_period' => 'required|integer|min:0',
            'short_description' => 'required|string',
            'detailed_description' => 'nullable|string',

            // Khi sửa, ảnh là KHÔNG bắt buộc (nullable). Nhưng nếu up thì phải chuẩn.
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:51200',
            'primary_image_index' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'sku.unique' => 'Mã SKU này đã được sử dụng cho một sản phẩm khác.',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá bán gốc.',
            'images.*.image' => 'File tải lên phải là hình ảnh.',
            'images.*.max' => 'Dung lượng mỗi ảnh không được vượt quá 50MB.',
        ];
    }
}
