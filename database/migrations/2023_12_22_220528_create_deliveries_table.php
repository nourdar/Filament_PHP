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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('wilaya_id')->nullable();
            $table->string('commune_id')->nullable();
            $table->string('wilaya_name')->nullable();
            $table->string('commune_name')->nullable();
            $table->string('is_wilaya')->nullable()->default(false);
            $table->string('is_commune')->nullable()->default(false);
            $table->string('is_center')->nullable()->default(false);
            $table->string('home')->nullable();
            $table->string('desk')->nullable();
            $table->string('retour')->nullable();
            $table->string('is_deliverable')->nullable();
            $table->string('has_stop_desk')->nullable()->default(false);
            $table->string('zone')->nullable();
            $table->string('center_id')->nullable();
            $table->string('center_name')->nullable();
            $table->string('center_address')->nullable();
            $table->string('center_gps')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
