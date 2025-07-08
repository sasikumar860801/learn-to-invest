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
         Schema::create('users_portfolio', function (Blueprint $table) {
            $table->id();
            $table->string('stock_id', 255);
            $table->unsignedBigInteger('user_id');
            $table->string('stock_name', 255);
            $table->integer('quantity');
            $table->decimal('buy_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->dateTime('buy_date');
            $table->json('avg');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
