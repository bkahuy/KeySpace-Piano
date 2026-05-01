<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // 1. Hiển thị danh sách đánh giá
    public function index()
    {
        // Lấy đánh giá kèm theo thông tin Khách hàng (user) và Sản phẩm (product)
        $reviews = Review::with(['user', 'product'])->orderBy('id', 'desc')->paginate(10);
        return view('admin.review.index', compact('reviews'));
    }

    // 2. Ẩn / Hiện đánh giá
    public function toggleStatus($id)
    {
        $review = Review::findOrFail($id);

        // Đảo ngược trạng thái (1 thành 0, 0 thành 1)
        $review->is_approved = $review->is_approved == 0 ? 1 : 0;
        $review->save();

        $message = $review->is_approved == 1 ? 'Đã hiển thị đánh giá!' : 'Đã ẩn đánh giá khỏi trang web!';
        return redirect()->back()->with('success', $message);
    }

    // 3. Xóa vĩnh viễn đánh giá
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Đã xóa đánh giá thành công!');
    }
}
