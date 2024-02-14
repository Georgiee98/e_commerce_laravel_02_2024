<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('shipping_address');
            $table->string('billing_address');
            $table->string('payment_method');
            $table->string('credit_card_number')->nullable();
            $table->string('credit_card_expiration')->nullable();
            $table->string('credit_card_cvv')->nullable();
            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->date('estimated_delivery_date')->nullable();
            $table->text('special_instructions')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            ;
            $table->decimal('total_amount', 10, 2);
            $table->decimal('tax_amount', 10, 2)->nullable();
            ;
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->string('customer_email')->nullable();
            ;
            $table->string('customer_phone')->nullable();
            $table->string('customer_name')->nullable();
            $table->boolean('newsletter_subscription')->default(false);
            $table->boolean('terms_accepted')->default(false);
            $table->text('order_notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};