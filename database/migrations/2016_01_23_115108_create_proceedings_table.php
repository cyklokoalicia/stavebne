<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProceedingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proceedings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->text('description', 65535)->nullable();
			$table->string('file_reference')->nullable();
			$table->text('aplicant', 65535)->nullable();
			$table->boolean('building_change')->default(0);
			$table->date('notified_at')->nullable();
			$table->date('decided_at')->nullable();
			$table->date('posted_at')->nullable();
			$table->date('droped_at')->nullable();
			$table->integer('project_id')->unsigned()->index('proceedings_project_id_foreign');
			$table->integer('proceeding_type_id')->unsigned()->index('proceedings_proceeding_type_id_foreign');
			$table->integer('proceeding_phase_id')->unsigned()->index('proceedings_proceeding_phase_id_foreign');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('proceedings');
	}

}
