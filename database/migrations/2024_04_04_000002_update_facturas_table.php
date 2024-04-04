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
        Schema::table('facturas', function(Blueprint $table) {
            $table->unique('id_pedido', 'uk_facturas_pedido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock', function(Blueprint $table) {
            $table->dropUnique('uk_facturas_pedido');
        });
    }
};
