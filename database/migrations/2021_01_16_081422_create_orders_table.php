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
            $table->foreignId('dish_id')->constrained('dishes');
            $table->integer('quantity');
            $table->bigInteger('unit_price')->comment('in paise');
            $table->bigInteger('amount')->comment('in paise');
            $table->tinyInteger('status')->default(1)->comment('1: processing, 2: accepted, 3: completed, 4: rejected');
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
