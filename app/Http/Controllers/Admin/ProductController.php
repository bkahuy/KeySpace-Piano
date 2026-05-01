<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\UpdateProductRequest;

class ProductController extends Controller
{
    // 1. Hiển thị danh sách sản phẩm (Dùng Eager Loading với 'category', 'brand')
    public function index()
    {
        // Phân trang: 10 sản phẩm 1 trang, sắp xếp mới nhất lên đầu
        $products = Product::with(['category', 'brand'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    // 2. Hiển thị Form thêm mới sản phẩm
    public function create()
    {
        // Lấy danh sách danh mục và thương hiệu để hiện ra thẻ <select>
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.create', compact('categories', 'brands'));
    }
    // 3. Xử lý lưu Sản phẩm mới và Upload Ảnh
    public function store(StoreProductRequest $request)
    {
        // Toàn bộ dữ liệu đầu vào đã được validate ở file 'StoreProductRequest'
        // Bạn có thể lấy mảng dữ liệu sạch bằng hàm $request->validated()
        $data = $request->validated();

        // Bắt đầu Database Transaction
        DB::beginTransaction();

        try {
            // 1. TẠO SẢN PHẨM TRONG BẢNG 'products'
            // Tự động tạo 'slug' từ tên đàn
            $data['slug'] = Str::slug($data['name']);
            // Mặc định sản phẩm mới sẽ hiển thị
            $data['is_active'] = true;

            $product = Product::create($data);

            // 2. XỬ LÝ UPLOAD VÀ LƯU ẢNH (Bảng 'product_images' và thư mục 'storage')
            if ($request->hasFile('images')) {

                // Lấy mảng các file ảnh
                $images = $request->file('images');
                // Lấy chỉ số ảnh chính được chọn từ form (mặc định là 0)
                $primaryIndex = (int)$request->primary_image_index;

                // Lặp qua từng file ảnh để lưu
                foreach ($images as $index => $image) {

                    // A. LƯU TỆP TIN VẬT LÝ VÀO THƯ MỰC 'storage/app/public/uploads/products'
                    // Hàm store() sẽ tự đặt tên ngẫu nhiên cho file để không bị trùng
                    // Nó trả về đường dẫn dạng: 'uploads/products/ten-file.jpg'
                    $storedPath = $image->store('uploads/products', 'public');

                    // B. LƯU THÔNG TIN ẢNH VÀO BẢNG 'product_images'
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $storedPath, // Chỉ lưu đường dẫn tương đối
                        // Đánh dấu ảnh chính nếu $index trùng với $primaryIndex
                        'is_primary' => ($index === $primaryIndex),
                    ]);
                }
            }

            // Nếu qua hết các bước OK, chốt lưu vào CSDL
            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Đã thêm đàn piano "' . $product->name . '" thành công cùng ' . count($images) . ' hình ảnh!');

        } catch (\Exception $e) {
            // Nếu có bất kỳ lỗi nào xảy ra trong Transaction
            DB::rollBack();

            // (Nâng cao) Tại đây bạn nên xóa các file ảnh đã upload dở vào storage
            // để tránh rác file nếu bạn muốn làm chặt chẽ hơn.

            return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình thêm sản phẩm. Vui lòng thử lại!')->withInput();
        }
    }
    // 4. Hiển thị Form Sửa sản phẩm (Chuẩn bị cho bước sau)
    public function edit($id)
    {
        // Lấy sản phẩm kèm theo hình ảnh
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    // 5. Xử lý Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        // 1. TẠO BẢN SAO ĐƯỜNG DẪN ẢNH (Chưa xóa vội)
        // Lưu lại các đường dẫn này ra một mảng trước khi thao tác với DB
        $imagePathsToDelete = [];
        foreach ($product->images as $image) {
            $imagePathsToDelete[] = $image->getRawOriginal('image_path');
        }

        try {
            DB::beginTransaction();

            // 2. Xóa dữ liệu ảnh trong bảng product_images
            $product->images()->delete();

            // 3. Xóa sản phẩm trong bảng products
            $product->delete();

            // 4. CHỐT DỮ LIỆU DB (Đến đây chắc chắn không bị khóa ngoại)
            DB::commit();

            // 5. XÓA FILE VẬT LÝ (Chỉ chạy khi DB commit thành công)
            foreach ($imagePathsToDelete as $imagePath) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
                }
            }

            return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm và dọn dẹp hình ảnh thành công!');

        } catch (\Exception $e) {
            // Nếu DB văng lỗi (ví dụ: vướng đơn hàng), code sẽ nhảy ngay xuống đây
            // DB được Rollback, và mảng $imagePathsToDelete ở trên bị hủy bỏ
            // -> File ảnh trên ổ cứng vẫn an toàn tuyệt đối!
            DB::rollBack();

            return redirect()->route('products.index')->with('error', 'Không thể xóa! Sản phẩm này đã phát sinh đơn hàng, bạn chỉ có thể ẩn nó đi.');
        }
    }
    // 6. Xử lý lưu dữ liệu Cập nhật (Update)
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();

        try {
            DB::beginTransaction();

            // 1. Cập nhật thông tin chữ
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
            $product->update($data);

            // 2. Xử lý Ảnh (Nếu admin CÓ chọn upload ảnh mới)
            if ($request->hasFile('images')) {

                // A. Xóa sạch file ảnh cũ trong ổ cứng để dọn rác
                foreach ($product->images as $oldImage) {
                    $oldPath = $oldImage->getRawOriginal('image_path');
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                    }
                }
                // Xóa dữ liệu ảnh cũ trong Database
                $product->images()->delete();

                // B. Lưu bộ ảnh mới vào
                $images = $request->file('images');
                $primaryIndex = (int)$request->primary_image_index;

                foreach ($images as $index => $image) {
                    $storedPath = $image->store('uploads/products', 'public');

                    \App\Models\ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $storedPath,
                        'is_primary' => ($index === $primaryIndex),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Đã cập nhật đàn piano "' . $product->name . '" thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật. Vui lòng thử lại!')->withInput();
        }
    }
}
