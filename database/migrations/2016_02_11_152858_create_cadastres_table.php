<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadastresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cadastres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
			$table->integer('city_district_id')->unsigned();			
			
			$table->foreign('city_district_id')->references('id')->on('city_districts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('cadastres', function (Blueprint $table)
        {
            $table->dropForeign('cadastres_city_district_id_foreign');
        });
		
        Schema::drop('cadastres');
    }
}
