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
        Schema::table('stock', function(Blueprint $table) {
            $table->unique('id_producto', 'uk_stock_producto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock', function(Blueprint $table) {
            $table->dropUnique('uk_stock_producto');
        });
    }
};
