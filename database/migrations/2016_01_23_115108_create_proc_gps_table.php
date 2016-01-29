<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProcGpsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proc_gps', function(Blueprint $table)
		{
			$table->float('lat', 10, 0)->nullable();
			$table->float('lon', 10, 0)->nullable();
			$table->float('dev1', 10, 0)->nullable();
			$table->float('dev2', 10, 0)->nullable();
			$table->integer('id_proceeding')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('proc_gps');
	}

}
