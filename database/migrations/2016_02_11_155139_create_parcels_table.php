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
            $table->increments('id');
            $table->string('name')->default(null);
			$table->char('type', 1);
			$table->decimal('gps_lon', 11, 7);
			$table->decimal('gps_lat', 11, 7);
			$table->integer('kadaster_id')->unsigned();
			$table->integer('proceeding_id')->unsigned();	
			$table->softDeletes();
			
			$table->foreign('kadaster_id')->references('id')->on('kadasters');
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
            $table->dropForeign('parcels_kadaster_id_foreign');
			$table->dropForeign('parcels_file_id_foreign');
        });
		
        Schema::drop('parcels');
    }
}
