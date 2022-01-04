<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRestaurantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_restaurant', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('restaurant_id');
            // $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            // $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_restaurant');
    }
}
