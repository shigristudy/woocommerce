<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorcelWoocommerceOrderItemsTable extends Migration
{
    /**
     * Run the Migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woocommerce_order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('order_item_id', true);
            $table->text('order_item_name');
            $table->string('order_item_type', 200);
            $table->unsignedBigInteger('order_id');

            $table->index('order_id');
        });
    }

    /**
     * Reverse the Migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('woocommerce_order_items');
    }
}
