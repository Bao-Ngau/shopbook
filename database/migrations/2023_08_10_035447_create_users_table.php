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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name_user')->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->string('image_user')->nullable(true);
            $table->string('email_code')->nullable(true);
            $table->text('refresh_token')->nullable(true);
            $table->string('password')->nullable(false);
            $table->unsignedBigInteger('coupon_id')->nullable(true);
            $table->unsignedBigInteger('role_id')->default(1);
            $table->string('address')->nullable(true);
            $table->string('phone', 10)->nullable(true);
            $table->boolean('status_user')->default(1);
            $table->date('created_date_user')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
