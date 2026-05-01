<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // 1. Hàm Thêm sản phẩm vào giỏ hàng
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'redirect_to' => 'nullable|in:cart,checkout',
        ]);

        $productId = $request->product_id;
        $quantity = (int) $request->quantity;

        $product = Product::with('primaryImage')->findOrFail($productId);

        if ($product->stock_quantity <= 0) {
            return redirect()->back()->with('error', 'Sản phẩm hiện đang hết hàng!');
        }

        $cart = session()->get('cart', []);
        $currentQuantity = $cart[$productId]['quantity'] ?? 0;

        if ($currentQuantity + $quantity > $product->stock_quantity) {
            return redirect()->back()->with('error', 'Số lượng vượt quá tồn kho!');
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $price = $product->sale_price ? $product->sale_price : $product->price;

            $cart[$productId] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $price,
                'image' => $product->primaryImage ? $product->primaryImage->image_path : null,
                'slug' => $product->slug,
            ];
        }

        session()->put('cart', $cart);

        if ($request->redirect_to === 'checkout') {
            return redirect()->route('checkout.index')
                ->with('success', 'Đã thêm "' . $product->name . '" vào giỏ hàng. Vui lòng kiểm tra thông tin thanh toán!');
        }

        return redirect()->back()
            ->with('success', 'Đã thêm "' . $product->name . '" vào giỏ hàng!');
    }


    // 2. Hàm Hiển thị trang Giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('customer.cart.index', compact('cart'));
    }

    // 3. Hàm Cập nhật số lượng
    public function update(Request $request)
    {
        $id = $request->id;
        $quantity = $request->quantity;

        // Lấy giỏ hàng hiện tại
        $cart = session()->get('cart');

        // Kiểm tra xem sản phẩm có trong giỏ không và số lượng phải lớn hơn 0
        if(isset($cart[$id]) && $quantity > 0) {

            // Cập nhật lại số lượng mới
            $cart[$id]['quantity'] = $quantity;

            // Lưu lại đè lên Session cũ
            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Đã cập nhật số lượng thành công!');
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
    }

    // 4. Hàm Xóa sản phẩm khỏi giỏ
    public function remove($id)
    {
        // Lấy giỏ hàng hiện tại
        $cart = session()->get('cart');

        // Nếu ID tồn tại trong giỏ hàng
        if(isset($cart[$id])) {
            // Dùng hàm unset của PHP để xóa phần tử khỏi mảng
            unset($cart[$id]);

            // Lưu mảng mới (đã bị xóa 1 món) vào lại Session
            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        }

        return redirect()->back();
    }
}
