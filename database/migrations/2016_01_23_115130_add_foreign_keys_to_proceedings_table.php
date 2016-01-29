<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProceedingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('proceedings', function(Blueprint $table)
		{
			$table->foreign('proceeding_phase_id')->references('id')->on('proceeding_phases')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('proceeding_type_id')->references('id')->on('proceeding_types')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('project_id')->references('id')->on('projects')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('proceedings', function(Blueprint $table)
		{
			$table->dropForeign('proceedings_proceeding_phase_id_foreign');
			$table->dropForeign('proceedings_proceeding_type_id_foreign');
			$table->dropForeign('proceedings_project_id_foreign');
		});
	}

}
