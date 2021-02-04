<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->integer('user_id');
            $table->string('department');
            $table->string('site');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->string('user_fullname');
            $table->string('identifier');
            $table->integer('supplier_id');
            $table->string('supplier_name');
            $table->string('desc');
            $table->integer('unit');
            $table->string('price');
            $table->string('rabatt');
            $table->string('net_price');
            $table->string('price_unit');
            $table->string('total_price');
            $table->string('group');
            $table->string('type');
            $table->boolean('ordered');
            $table->string('reciving_person')->nullable();
            $table->boolean('complete')->nullable();
            $table->boolean('partial')->nullable();
            $table->date('c_date')->nullable();
            $table->date('p_date')->nullable();
            $table->date('created_at');
            $table->date('deleted_at');
            $table->date('updated_at');
            //$table->timestamps();
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
