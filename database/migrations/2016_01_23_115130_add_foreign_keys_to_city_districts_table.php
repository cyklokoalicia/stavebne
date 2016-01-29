<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCityDistrictsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('city_districts', function(Blueprint $table)
		{
			$table->foreign('district_id')->references('id')->on('districts')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('city_districts', function(Blueprint $table)
		{
			$table->dropForeign('city_districts_district_id_foreign');
		});
	}

}
