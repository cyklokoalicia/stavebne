<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title')->nullable();
			$table->text('description', 65535)->nullable();
			$table->text('aplicant', 65535)->nullable();
			$table->date('posted_at')->nullable();
			$table->date('droped_at')->nullable();
			$table->integer('city_district_id')->unsigned()->index('projects_city_district_id_foreign');
			$table->timestamps();
			$table->softDeletes();
			$table->float('lat', 10, 0)->nullable();
			$table->float('lon', 10, 0)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects');
	}

}
