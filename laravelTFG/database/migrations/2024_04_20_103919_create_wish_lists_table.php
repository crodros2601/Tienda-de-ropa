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
        Schema::create('wish_lists', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::table('wish_lists', function (Blueprint $table) {
            $table->dropForeign('wish_lists_producto_id_foreign');
        });
    
        Schema::dropIfExists('wish_lists');    }
};
