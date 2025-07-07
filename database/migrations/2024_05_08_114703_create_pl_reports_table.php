<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pl_reports', function (Blueprint $table) {
            $table->id();
            $table->string('stock_id', 255);
            $table->string('stock_name', 255);
            $table->string('Market_Cap', 255);
            $table->integer('quantity');
            $table->integer('buy_price');
            $table->integer('sell_price');
            $table->integer('total_buy_price');
            $table->integer('total_sell_price');
            $table->datetime('buy_date');
            $table->datetime('sell_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pl_reports');
    }
}
