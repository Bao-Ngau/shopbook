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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name_book')->nullable(false);
            $table->string('image_book');
            $table->text('description')->nullable(false);
            $table->unsignedBigInteger('author_id')->nullable(false);
            $table->unsignedBigInteger('category_id')->nullable(false);
            $table->integer('price')->nullable(false);
            $table->integer('sale')->nullable(false);
            $table->integer('price_sale')->nullable(false);
            $table->boolean('status_book')->default(0);
            $table->dateTime('create_date_book')->nullable(false);
            $table->string('create_by_book', 50)->nullable(false);
            $table->dateTime('updated_date_book');
            $table->string('updated_at_book', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book');
    }
};
