<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_product', function (Blueprint $table) {
            $table->char('id', 36);
            $table->char('company_id', 36);
            $table->char('category_id', 36);
            $table->char('parent_id', 36);
            $table->char('unit_id', 36);
            $table->char('code', 20);
            $table->char('name', 128);
            $table->decimal('cost_price', 16, 8);
            $table->decimal('price', 16, 8);
            
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
        Schema::dropIfExists('tb_product');
    }
}
