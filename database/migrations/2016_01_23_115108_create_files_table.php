<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('original_filename', 65535);
			$table->string('mime_type');
			$table->string('file_extension');
			$table->string('caption');
			$table->integer('file_size');
			$table->text('metadata', 65535)->nullable();
			$table->integer('proceeding_id')->unsigned()->index('files_proceeding_id_foreign');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('files');
	}

}
