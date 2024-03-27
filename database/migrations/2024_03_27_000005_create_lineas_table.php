<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lineas', function(Blueprint $table) {
            $table->id();
            $table->float('precio')->default(0);
            $table->integer('cantidad')->default(1);
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_pedido');

            $table->foreign('id_producto')->references('id')->on('productos');
            $table->foreign('id_pedido')->references('id')->on('pedidos');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lineas');
    }
};
