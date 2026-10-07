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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('wedding_card_id')->nullable()->constrained()->onDelete('set null');
        $table->string('order_code')->unique(); // Mã đơn hàng (VD: ORD123456)
        $table->decimal('amount', 12, 2); // Số tiền
        $table->string('payment_method')->default('transfer'); // Phương thức (chuyển khoản, v.v.)
        $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending'); // Trạng thái
        $table->timestamp('paid_at')->nullable(); // Thời gian thanh toán
        $table->text('note')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
