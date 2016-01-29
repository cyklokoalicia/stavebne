<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParcelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('parcels', function(Blueprint $table)
		{
			$table->integer('id_proceeding')->unsigned();
			$table->string('parcel', 20)->index('parcel');
			$table->integer('lv')->nullable();
			$table->char('type', 1);
			$table->integer('id_kadastre')->index('id_kadastre');
			$table->float('lat', 10, 0)->nullable();
			$table->float('lon', 10, 0)->nullable();
			$table->integer('city_district_id')->index('city_district_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('parcels');
	}

}
