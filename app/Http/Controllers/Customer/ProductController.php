<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Review;

class ProductController extends Controller
{
    public function show($slug)
    {
        // 1. Tìm sản phẩm theo slug, lấy kèm theo Ảnh, Thương hiệu, và Danh mục
        // Dùng firstOrFail() để nếu khách gõ sai link sẽ tự động nhảy ra trang lỗi 404 (Not Found)
        $product = Product::with(['images', 'brand', 'category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // 2. Lấy các sản phẩm liên quan (Cùng danh mục, khác ID sản phẩm hiện tại)
        $relatedProducts = Product::with(['primaryImage', 'brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder() // Lấy ngẫu nhiên cho đa dạng
            ->take(4)
            ->get();

        return view('customer.product.show', compact('product', 'relatedProducts'));
    }

    public function index(Request $request)
    {
        // 1. Khởi tạo câu truy vấn gốc (Chỉ lấy sản phẩm còn hàng)
        $query = Product::where('stock_quantity', '>', 0);

        // Tìm kiếm theo Tên sản phẩm hoặc Mã SKU
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('sku', 'like', '%' . $keyword . '%');
            });
        }

        // 2. Lọc theo Danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // 3. Lọc theo Thương hiệu
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // 4. Lọc theo Khoảng giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 5. Lọc theo Tình trạng (Mới/Cũ)
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // 6. Sắp xếp (Mới nhất, Giá tăng/giảm)
        if ($request->filled('sort')) {
            if ($request->sort == 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        } else {
            // Mặc định luôn xếp mới nhất lên đầu
            $query->orderBy('created_at', 'desc');
        }

        // 7. Thực thi truy vấn, phân trang 12 sp/trang và GIỮ LẠI THAM SỐ TRÊN URL
        $products = $query->paginate(12)->withQueryString();

        // Lấy danh mục và thương hiệu để hiển thị ra Menu lọc bên trái
        $categories = Category::all();
        $brands = Brand::all();

        return view('customer.product.index', compact('products', 'categories', 'brands'));
    }

    // Hàm lưu đánh giá
    public function postReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'comment.required' => 'Vui lòng nhập nội dung đánh giá.',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => 1 // Mặc định cho hiện luôn (Nếu bạn muốn duyệt trước thì để là 0)
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
    }
}
