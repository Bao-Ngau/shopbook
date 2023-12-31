<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->integer('quantity')->default(1);
            $table->integer('status_cart')->default(0)->nullable(false);
            $table->unsignedBigInteger('pay_id')->default(1);
            $table->integer('total_money')->default(0);
            $table->dateTime('created_date_cart')->nullable(false);
            $table->dateTime('created_by_cart')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
