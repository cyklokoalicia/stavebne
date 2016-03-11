<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnershipListsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ownership_lists', function (Blueprint $table)
		{
			$table->integer('number')->index();
			$table->integer('file_id')->unsigned();
			$table->timestamps();


			$table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ownership_lists', function (Blueprint $table)
		{
			$table->dropForeign('ownership_lists_file_id_foreign');
			$table->dropIndex('ownership_lists_number_index');
		});

		Schema::drop('ownership_lists');
	}
}
