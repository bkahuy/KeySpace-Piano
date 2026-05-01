<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Dùng restrictOnDelete để không cho xóa danh mục/thương hiệu nếu vẫn còn sản phẩm
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('brand_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            // Đàn piano giá trị lớn (tiền tỷ VND), nên dùng unsignedBigInteger
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('sale_price')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->enum('condition', ['new', 'used'])->default('new');
            $table->string('color')->nullable();
            $table->integer('warranty_period')->comment('Tính theo tháng');
            $table->text('short_description')->nullable();
            $table->longText('detailed_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
