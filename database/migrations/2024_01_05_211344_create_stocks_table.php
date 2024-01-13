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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
            $table->string('product_name');
            $table->string('order_id');
            $table->string('order_item_id');
            $table->string('type')->default('shipped'); // shipping or physhique
            $table->string('move_type')->nullable(); // in or out
            $table->string('qte')->nullable(); // in or out
            $table->longText('options')->nullable(); // Product Mesures
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
