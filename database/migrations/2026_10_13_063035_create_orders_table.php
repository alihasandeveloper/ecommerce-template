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
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->index();
            $table->string('status')->default('pending')->index(); // pending, processing, on_hold, completed, cancelled, refunded
            $table->string('currency', 3)->default('BDT');

            // Totals
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('discount_total', 12, 2)->default(0.00);
            $table->decimal('shipping_total', 12, 2)->default(0.00);
            $table->decimal('tax_total', 12, 2)->default(0.00);
            $table->decimal('grand_total', 12, 2)->default(0.00);

            // Coupon
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->string('coupon_code')->nullable();

            // Shipping
            $table->foreignId('shipping_method_id')->nullable()->constrained('shipping_methods')->nullOnDelete();
            $table->string('shipping_method_title')->nullable();

            // Payment
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, failed, refunded

            // Billing snapshot
            $table->string('billing_name');
            $table->string('billing_phone');
            $table->string('billing_email')->nullable();
            $table->string('billing_address');
            $table->string('billing_city');
            $table->string('billing_state')->nullable();
            $table->string('billing_postcode')->nullable();
            $table->string('billing_country', 2)->default('BD');

            // Shipping snapshot
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_country', 2)->default('BD');

            // Misc
            $table->text('customer_note')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->dateTime('paid_at')->nullable();
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
