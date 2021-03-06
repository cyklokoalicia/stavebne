<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {		
        Schema::create('city_districts', function (Blueprint $table) {						
            $table->increments('id');
			$table->string('name');			
			$table->integer('district_id')->unsigned();
			
			$table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('city_districts', function (Blueprint $table)
        {
            $table->dropForeign('city_districts_district_id_foreign');
        });	
		
        Schema::drop('city_districts');
    }
}
