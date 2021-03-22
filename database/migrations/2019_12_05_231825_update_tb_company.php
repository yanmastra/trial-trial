<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTbCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tb_company', function (Blueprint $table) {
            $table->char('id', 36)->change();
            $table->char('name', 128)->change();
            $table->char('phone', 14)->change();
            $table->char('email', 128)->change();
            $table->char('alamat', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
