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
        Schema::create('users_pl_reports', function (Blueprint $table) {
            $table->id();
            $table->string('stock_id', 255);
            $table->integer('user_id');
            $table->json('avg');
            $table->string('stock_name', 255);
            $table->string('Market_Cap', 255);
            $table->integer('quantity');
            $table->decimal('buy_price', 10, 2);
            $table->decimal('sell_price', 10, 2);
            $table->decimal('total_buy_price', 10, 2);
            $table->decimal('total_sell_price', 10, 2);
            $table->datetime('buy_date');
            $table->datetime('sell_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
