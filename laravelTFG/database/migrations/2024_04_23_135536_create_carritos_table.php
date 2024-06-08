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
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('talla_id');
            $table->integer('cantidadProducto');
            $table->decimal('precio',8,2);
            $table->timestamps();
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('talla_id')->references('id')->on('tallas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carritos');
    }
};
