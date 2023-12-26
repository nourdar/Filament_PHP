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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('email')->nullable();
            $table->text('telephone')->nullable();
            $table->string('wilaya_depart')->nullable();
            $table->longText('transport_api')->nullable();
            $table->string('wilaya_depart')->nullable();
            $table->longText('head_code')->nullable();
            $table->longText('slides')->nullable();
            $table->string('address')->nullable();
            $table->string('facebook_page')->nullable();
            $table->string('instagram_page')->nullable();
            $table->string('pinterest_page')->nullable();
            $table->string('twitter_page')->nullable();

            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
