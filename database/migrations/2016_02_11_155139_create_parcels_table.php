<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcels', function (Blueprint $table) {
            $table->string('number')->index();
            $table->integer('ownership_document_number');
			$table->char('type', 1);
			$table->decimal('gps_lon', 11, 7);
			$table->decimal('gps_lat', 11, 7);
			$table->integer('cadaster_id')->unsigned();
			$table->integer('file_id')->unsigned();	
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('cadaster_id')->references('id')->on('cadasters');
			$table->foreign('file_id')->references('id')->on('files');
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('parcels', function (Blueprint $table)
        {
            $table->dropForeign('parcels_cadaster_id_foreign');
			$table->dropForeign('parcels_file_id_foreign');
        });
		
        Schema::drop('parcels');
    }
}
