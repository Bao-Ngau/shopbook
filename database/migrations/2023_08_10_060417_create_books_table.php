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
            $table->text('description')->nullable(true);
            $table->text('advantage')->nullable(true);
            $table->unsignedBigInteger('author_id')->nullable(true);
            $table->unsignedBigInteger('category_id')->nullable(true);
            $table->integer('count_book')->default(0);
            $table->integer('price')->nullable(false);
            $table->integer('sale')->default(0);
            $table->integer('price_sale')->nullable(false);
            $table->integer('sold')->default(0);
            $table->boolean('status_book')->default(1);
            $table->dateTime('create_date_book');
            $table->string('create_by_book', 50);
            $table->dateTime('updated_date_book')->nullable(true);
            $table->string('updated_at_book', 50)->nullable(true);
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
