<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
          $table->bigIncrements('id');
          $table->string('name');
          $table->string('description')->nullable();
          $table->double('content', 8, 2);
          $table->double('net_weight', 8, 2)->nullable();
          $table->double('stock_on_hand', 8, 2)->nullable();
          $table->double('purchase_price', 8, 2)->nullable();
          $table->double('unit_price', 8, 2);
          $table->string('type', 1);
          $table->unsignedBigInteger('category_id');
          $table->foreign('category_id')->references('id')->on('categories');
          $table->unsignedBigInteger('supplier_id')->nullable();
          $table->foreign('supplier_id')->references('id')->on('suppliers');
          $table->unsignedBigInteger('warehouse_id')->nullable();
          $table->foreign('warehouse_id')->references('id')->on('warehouse');
          $table->unsignedBigInteger('section_id')->nullable();
          $table->foreign('section_id')->references('id')->on('sections');
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users');
          $table->unsignedBigInteger('brand_id')->nullable();
          $table->foreign('brand_id')->references('id')->on('brand');
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
