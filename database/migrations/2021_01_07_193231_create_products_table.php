<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id');
            $table->integer('product_id');
            $table->string('desc');
            $table->integer('unit');
            $table->string('price');
            $table->string('rabatt');
            $table->string('net_price');
            $table->string('price_unit');
            $table->string('group');
            $table->string('supplier_name');
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
        Schema::dropIfExists('products');
    }
}
