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
            $table->text('filename');
            $table->string('mime_type');
            $table->string('caption');
            $table->integer('filesize');
            $table->integer('project_id')->unsigned();
			$table->timestamps();
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
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
            $table->dropForeign('files_project_id_foreign');
        });
		
        Schema::drop('files');
    }
}
