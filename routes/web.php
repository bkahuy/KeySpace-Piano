<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\OrderTrackingController;
use App\Http\Controllers\Admin\PromotionController;


//Route Customer
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.post');
    Route::get('/dang-ky', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.submit');
});
//Public route
Route::get('/', [HomeController::class, 'index'])->name('home'); //trang chu
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('product.show'); //chi tiet san pham
Route::get('/san-pham', [ProductController::class, 'index'])->name('product.index');
// Tra cứu đơn hàng
Route::get('/tra-cuu-don-hang', [OrderTrackingController::class, 'index'])->name('tracking.index');
Route::post('/tra-cuu-don-hang', [OrderTrackingController::class, 'track'])->name('tracking.track');
//Route Giỏ hàng
Route::prefix('gio-hang')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index'); // Xem giỏ hàng
    Route::post('/them', [CartController::class, 'add'])->name('cart.add'); // Thêm vào giỏ
    Route::post('/cap-nhat', [CartController::class, 'update'])->name('cart.update');
    Route::get('/xoa/{id}', [CartController::class, 'remove'])->name('cart.remove');
});
// Nhóm Route Thanh toán
Route::prefix('thanh-toan')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index'); // Form điền thông tin
    Route::post('/', [CheckoutController::class, 'process'])->name('checkout.process'); // Xử lý lưu DB
    Route::get('/thanh-cong', [CheckoutController::class, 'success'])->name('checkout.success'); // Trang báo thành công
    Route::get('/vnpay/return', [CheckoutController::class, 'vnpayReturn'])->name('vnpay.return');
});
// Đăng xuất
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');

// Nhóm Route khu vực Admin (Bọc bởi Middleware 'admin')
Route::prefix('admin')->middleware(['auth', 'admin', PreventBackHistory::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('products', AdminProductController::class);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    Route::resource('categories', CategoryController::class);
    Route::resource('customers', CustomerController::class);
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/toggle-status', [ReviewController::class, 'toggleStatus'])->name('reviews.toggleStatus');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::resource('promotions', PromotionController::class);


});
// Route dành cho Khách hàng đã đăng nhập
Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    Route::get('/lich-su-mua-hang', [CustomerOrderController::class, 'index'])->name('customer.orders.history');
    Route::get('/chi-tiet-don-hang/{id}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
    Route::put('/chi-tiet-don-hang/{id}/huy', [CustomerOrderController::class, 'cancel'])->name('customer.orders.cancel');
    Route::post('/products/{id}/review', [ProductController::class, 'postReview'])->name('products.review');
    Route::put('/trang-ca-nhan', [ProfileController::class, 'update'])->name('profile.update');
});
