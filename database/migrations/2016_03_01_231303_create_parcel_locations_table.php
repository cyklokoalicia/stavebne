<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParcelLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcel_locations', function (Blueprint $table) {
            $table->string('name',20);
            $table->integer('ownership_document_number');
			$table->integer('cadastre_id')->unsigned();
			$table->text('address');
			$table->decimal('gps_lon', 11, 7);
			$table->decimal('gps_lat', 11, 7);
			
			$table->foreign('cadastre_id')->references('id')->on('cadastre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('parcel_locations', function (Blueprint $table)
        {
            $table->dropForeign('parcel_locations_cadastre_id_foreign');
        });	
		
        Schema::drop('parcel_locations');
    }
}
