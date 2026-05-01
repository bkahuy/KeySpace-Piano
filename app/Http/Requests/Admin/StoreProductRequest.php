<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    // Cho phép thực hiện request này
    public function authorize(): bool
    {
        return true;
    }

    // Các quy tắc kiểm tra dữ liệu
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id', // Phải chọn danh mục có thật
            'brand_id' => 'required|exists:brands,id',     // Phải chọn thương hiệu có thật
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:100', // SKU phải là duy nhất
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price', // Giá sale phải nhỏ hơn giá gốc
            'stock_quantity' => 'required|integer|min:0',
            'condition' => 'required|in:new,used',
            'color' => 'nullable|string|max:100',
            'warranty_period' => 'required|integer|min:0',
            'short_description' => 'required|string',
            'detailed_description' => 'nullable|string',

            // Validate mảng Ảnh upload
            'images' => 'required|array|min:1', // Bắt buộc phải upload ít nhất 1 ảnh
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:51200', // Mỗi ảnh tối đa 50MB

            // Validate chỉ số ảnh chính
            'primary_image_index' => 'required|integer|min:0',
        ];
    }

    // Các thông báo lỗi tiếng Việt
    public function messages()
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu không hợp lệ.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'sku.required' => 'Vui lòng nhập mã SKU.',
            'sku.unique' => 'Mã SKU này đã tồn tại trên hệ thống.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'price.numeric' => 'Giá bán phải là số.',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá bán gốc.',
            'stock_quantity.required' => 'Vui lòng nhập số lượng tồn kho.',
            'warranty_period.required' => 'Vui lòng nhập thời gian bảo hành.',
            'short_description.required' => 'Vui lòng nhập mô tả ngắn.',

            'images.required' => 'Vui lòng upload ít nhất 1 hình ảnh cho sản phẩm.',
            'images.array' => 'Dữ liệu hình ảnh không hợp lệ.',
            'images.*.image' => 'Các file upload phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh chỉ chấp nhận các định dạng: jpeg, png, jpg, gif.',
            'images.*.max' => 'Dung lượng mỗi ảnh không được vượt quá 50MB.',

            'primary_image_index.required' => 'Vui lòng chọn 1 ảnh làm ảnh chính.',
        ];
    }
}
