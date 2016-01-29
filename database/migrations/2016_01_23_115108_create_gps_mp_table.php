<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGpsMpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gps_mp', function(Blueprint $table)
		{
			$table->string('parcel', 20)->index('parcel');
			$table->integer('lv')->nullable();
			$table->integer('id_kadastre')->index('id_kadastre');
			$table->text('address', 65535)->nullable();
			$table->float('lon', 10, 0);
			$table->float('lat', 10, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gps_mp');
	}

}
