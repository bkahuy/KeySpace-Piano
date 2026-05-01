<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('role', 'customer')->first();
        $product1 = Product::where('sku', 'YAM-U3H-001')->first();
        $product2 = Product::where('sku', 'ROL-RP501-001')->first();

        // Tạo 1 đơn hàng đã giao thành công (Để hiện doanh thu trên Dashboard)
        $order = Order::create([
            'user_id' => $customer->id,
            'order_code' => 'ORD-20260317-' . strtoupper(Str::random(5)),
            'total_amount' => $product1->price + ($product2->price * 2), // Mua 1 cây cơ, 2 cây điện
            'status' => 'delivered',
            'payment_method' => 'bank_transfer',
            'payment_status' => 'paid',
            'shipping_name' => $customer->name,
            'shipping_phone' => $customer->phone,
            'shipping_address' => $customer->address,
            'shipping_email' => $customer->email,
            'order_notes' => 'Giao hàng vào cuối tuần giúp mình nhé.',
        ]);

        // Thêm chi tiết đơn hàng (Mua cây đàn số 1)
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'unit_price' => $product1->price,
            'quantity' => 1,
        ]);

        // Thêm chi tiết đơn hàng (Mua 2 cây đàn số 2)
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'unit_price' => $product2->price,
            'quantity' => 2,
        ]);
    }
}
