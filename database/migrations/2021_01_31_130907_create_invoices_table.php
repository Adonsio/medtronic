<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('coupon');
            $table->string('supplier_name');
            $table->string('department');
            $table->string('user_fullname');
            $table->integer('net_price');
            $table->integer('gross_price');
            $table->integer('total_price');
            $table->date('order_date');
            $table->boolean('complete');
            $table->boolean('pending');

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
        Schema::dropIfExists('invoices');
    }
}