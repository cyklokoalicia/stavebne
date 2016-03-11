<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {			
            $table->increments('id');
            $table->text('original_filename');
            $table->string('mime_type');
			$table->string('file_extension');
            $table->string('caption');
			$table->string('url', 512)->collation('ascii_general_ci')->comment('URL to file');
            $table->integer('file_size')->unsigned()->comment('bytes');
			$table->decimal('gps_lon', 11, 7);
			$table->decimal('gps_lat', 11, 7);
			$table->integer('city_district_id')->unsigned()->comment('where it was downloaded');
			$table->integer('file_process_id')->unsigned();
			$table->integer('proceeding_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('city_district_id')->references('id')->on('city_districts');
			$table->foreign('file_process_id')->references('id')->on('file_processes');
            $table->foreign('proceeding_id')->references('id')->on('proceedings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('files', function (Blueprint $table)
        {
			$table->dropForeign('files_city_district_id_foreign');
			$table->dropForeign('files_file_process_id_foreign');
            $table->dropForeign('files_proceeding_id_foreign');
        });
		
        Schema::drop('files');
    }
}
