<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_stock', function (Blueprint $table) {
            $table->char('id', 36);
            $table->char('company_id', 36);
            $table->char('product_id', 36);
            $table->dateTime('input_date');
            $table->double('start_stock', 9, 2);
            $table->double('current_stock', 9, 2);
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
        Schema::dropIfExists('tb_stock');
    }
}
