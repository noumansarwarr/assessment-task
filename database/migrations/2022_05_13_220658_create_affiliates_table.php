<?php

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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('merchant_id');
            // TODO: Replace me with a brief explanation of why floats aren't the correct data type, and replace with the correct data type.

            // Because floats and doubles cannot accurately represent the base 10 multiples that we use for money.
            // A solution that works in just about any language is to use integers instead, and count cents.
            // For instance, 1025 would be 10.25, It would be better to use mutatot or accessor for this.
            // Or yes, we can use decimal but again this is not as much accurate
            $table->unsignedBigInteger('commission_rate')->default(0);
            $table->string('discount_code');
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
        Schema::dropIfExists('affiliates');
    }
};
