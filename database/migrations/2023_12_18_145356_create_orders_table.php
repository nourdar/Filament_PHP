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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("customer_id")
                    ->constrained('customers')
                    ->onDelete('cascade');
            $table->string("order_number")->unique();
            $table->enum("status", ['placed', 'confirmed', 'processing', 'shipped', 'paid', 'declined', 'back'])
            ->default('placed');
            $table->decimal("shipping_price")->nullable();
            $table->string("shipping_type")->nullable();
            $table->string("tracking")->nullable();
            $table->string("transport_provider")->nullable();
            $table->longText("notes")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
