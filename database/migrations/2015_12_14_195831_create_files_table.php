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
            $table->integer('file_size')->comment('bytes');
			$table->text('metadata')->nullable()->default(null);
			$table->integer('proceeding_id')->unsigned();
			$table->timestamps();
			
            $table->foreign('proceeding_id')->references('id')->on('proceedings')->onDelete('cascade');
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
            $table->dropForeign('files_proceeding_id_foreign');
        });
		
        Schema::drop('files');
    }
}