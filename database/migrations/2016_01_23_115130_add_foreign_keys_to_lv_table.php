<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLvTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lv', function(Blueprint $table)
		{
			$table->foreign('id_proceeding', 'lv_ibfk_1')->references('id')->on('proceedings')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lv', function(Blueprint $table)
		{
			$table->dropForeign('lv_ibfk_1');
		});
	}

}
