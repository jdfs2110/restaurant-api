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
        Schema::table('productos', function(Blueprint $table) {
            $table->unsignedBigInteger('id_categoria');

            $table->foreign('id_categoria')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function(Blueprint $table) {
            $table->dropForeign('productos_id_categoria_foreign');
            $table->dropColumn('id_categoria');
        });
    }
};
