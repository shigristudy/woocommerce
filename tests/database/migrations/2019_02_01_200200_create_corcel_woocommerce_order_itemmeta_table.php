<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorcelWoocommerceOrderItemmetaTable extends Migration
{
    /**
     * Run the Migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woocommerce_order_itemmeta', function (Blueprint $table) {
            $table->unsignedBigInteger('meta_id', true);
            $table->unsignedBigInteger('order_item_id');
            $table->string('meta_key', 200);
            $table->text('meta_value');

            $table->index(['order_item_id', 'meta_key']);
        });
    }

    /**
     * Reverse the Migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('woocommerce_order_itemmeta');
    }
}
