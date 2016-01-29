<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKadastresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kadastres', function(Blueprint $table)
		{
			$table->integer('id')->index('id');
			$table->string('name', 20);
			$table->integer('city_district')->index('city_district');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kadastres');
	}

}
