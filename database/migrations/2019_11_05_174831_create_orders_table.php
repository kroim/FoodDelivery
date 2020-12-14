<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('postcode');
            $table->string('email');
            $table->string('phone');
            $table->string('company')->nullable();
            $table->integer('service_hours');
            $table->string('payment_method');  // paypal, visa, master
            $table->string('payment_status');  // pending(cash payment), failed, completed, refunded
            $table->text('order_data');
            $table->text('remark')->nullable();
            $table->float('order_price');
            $table->string('transaction_id')->nullable();
            $table->string('restaurant_id');
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
}
