<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('portfolios', function (Blueprint $table) {
        $table->id();
        $table->string('stock_id', 255);
        $table->string('stock_name', 255);
        $table->string('Market_Cap', 255);
        $table->integer('quantity');
        $table->integer('buy_price');
        $table->integer('total_price');
        $table->datetime('buy_date');
        $table->timestamps(); // Adds created_at and updated_at columns
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolios');
    }
}
