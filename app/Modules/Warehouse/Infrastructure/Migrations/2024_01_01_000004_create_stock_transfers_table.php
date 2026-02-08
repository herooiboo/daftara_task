<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventory_items');
            $table->foreignId('base_warehouse_id')->constrained('warehouses');
            $table->foreignId('target_warehouse_id')->constrained('warehouses');
            $table->decimal('amount', 10, 2)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
