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
        Schema::create('pedidos', function(Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->integer('estado')->default(0);
            $table->double('precio')->default(0);
            $table->integer('numero_comensales')->default(1);
            $table->unsignedBigInteger('id_mesa');
            $table->unsignedBigInteger('id_usuario');

            $table->foreign('id_mesa')->references('id')->on('mesas');
            $table->foreign('id_usuario')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
