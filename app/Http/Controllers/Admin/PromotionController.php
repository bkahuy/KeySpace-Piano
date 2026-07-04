<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\User;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::withCount([
            'coupons',
            'coupons as used_coupons_count' => function ($query) {
                $query->where('is_used', true);
            },
        ])->latest()->paginate(10);

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatePromotion($request);

        $data['code_prefix'] = strtoupper($data['code_prefix']);
        $data['min_order_amount'] = $data['min_order_amount'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($data) {
            $promotion = Promotion::create($data);
            $this->syncCouponsForCustomers($promotion);
        });

        return redirect()->route('promotions.index')
            ->with('success', 'Đã tạo chương trình khuyến mãi và phát mã cho khách hàng.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $this->validatePromotion($request, $promotion->id);

        $data['code_prefix'] = strtoupper($data['code_prefix']);
        $data['min_order_amount'] = $data['min_order_amount'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($promotion, $data) {
            $promotion->update($data);
            $this->syncCouponsForCustomers($promotion);
        });

        return redirect()->route('promotions.index')
            ->with('success', 'Đã cập nhật chương trình khuyến mãi.');
    }

    public function destroy(Promotion $promotion)
    {
        if ($promotion->coupons()->where('is_used', true)->exists()) {
            return redirect()->back()
                ->with('error', 'Không thể xóa chương trình đã có mã được sử dụng. Hãy tắt trạng thái hoạt động.');
        }

        $promotion->delete();

        return redirect()->route('promotions.index')
            ->with('success', 'Đã xóa chương trình khuyến mãi.');
    }

    private function validatePromotion(Request $request, ?int $promotionId = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'code_prefix' => 'required|string|max:20|unique:promotions,code_prefix,' . $promotionId,
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|integer|min:1',
            'min_order_amount' => 'nullable|integer|min:0',
            'max_discount_amount' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Vui lòng nhập tên chương trình.',
            'code_prefix.required' => 'Vui lòng nhập tiền tố mã.',
            'code_prefix.unique' => 'Tiền tố mã này đã tồn tại.',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'ends_at.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);
    }

    private function syncCouponsForCustomers(Promotion $promotion): void
    {
        $customers = User::where('role', 'customer')->get();

        foreach ($customers as $customer) {
            UserCoupon::firstOrCreate(
                [
                    'user_id' => $customer->id,
                    'promotion_id' => $promotion->id,
                ],
                [
                    'code' => $promotion->code_prefix . '-' . $customer->id . '-' . strtoupper(Str::random(5)),
                ]
            );
        }
    }

    public static function assignPromotionCouponsToNewUser(User $user): void
    {
        $promotions = Promotion::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhereDate('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhereDate('ends_at', '>=', now());
            })
            ->get();

        foreach ($promotions as $promotion) {
            UserCoupon::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'promotion_id' => $promotion->id,
                ],
                [
                    'code' => $promotion->code_prefix
                        . '-' . $user->id
                        . '-' . strtoupper(Str::random(5)),
                ]
            );
        }
    }
}
