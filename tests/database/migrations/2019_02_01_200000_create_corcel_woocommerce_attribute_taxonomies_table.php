<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorcelWoocommerceAttributeTaxonomiesTable extends Migration
{
    /**
     * Run the Migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woocommerce_attribute_taxonomies', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_id', true);
            $table->string('attribute_name', 200);
            $table->string('attribute_label', 200)->nullable();
            $table->string('attribute_type', 20);
            $table->string('attribute_orderby', 20);
            $table->boolean('attribute_public')->default(1);

            $table->index('attribute_name');
        });
    }

    /**
     * Reverse the Migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('woocommerce_attribute_taxonomies');
    }
}
