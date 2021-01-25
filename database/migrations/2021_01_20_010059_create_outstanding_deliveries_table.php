<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutstandingDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outstanding_deliveries', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('supplier_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->string('type');
            $table->string('reciving_person')->nullable();
            $table->boolean('complete')->nullable();
            $table->boolean('partial')->nullable();
            $table->date('c_date')->nullable();
            $table->date('p_date')->nullable();
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
        Schema::dropIfExists('outstanding_deliveries');
    }
}
