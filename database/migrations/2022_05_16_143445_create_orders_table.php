<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained();
            $table->foreignId('affiliate_id')->nullable()->constrained();
            // TODO: Replace floats with the correct data types (very similar to affiliates table)

            // Because floats and doubles cannot accurately represent the base 10 multiples that we use for money.
            // A solution that works in just about any language is to use integers instead, and count floating points.
            // For instance, 1025 would be 10.25, It would be better to use mutatot or accessor for this.
            // Or yes, we can use decimal but again this is not as much accurate
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('commission_owed')->default(0);
            $table->string('payout_status')->default(Order::STATUS_UNPAID);
            $table->string('discount_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
