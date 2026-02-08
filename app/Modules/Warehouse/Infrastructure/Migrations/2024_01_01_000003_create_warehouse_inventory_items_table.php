<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventory_items');
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->decimal('stock', 10, 2)->nullable();
            $table->decimal('low_stock_threshold', 10, 2)->nullable();
            $table->foreignId('last_updated_by')->constrained('users');
            $table->timestamps();

            $table->unique(['inventory_id', 'warehouse_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_inventory_items');
    }
};
