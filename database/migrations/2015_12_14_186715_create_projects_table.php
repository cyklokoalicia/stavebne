<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
			$table->string('title')->nullable()->default(null);
			$table->text('description')->nullable()->default(null);
			$table->text('aplicant')->nullable()->default(null);
			$table->decimal('gps_lon', 11, 7);
			$table->decimal('gps_lat', 11, 7);
			$table->date('posted_at')->nullable()->default(null);
			$table->date('droped_at')->nullable()->default(null);
			$table->integer('city_district_id')->unsigned();
            $table->timestamps();
			$table->softDeletes();
			
			$table->foreign('city_district_id')->references('id')->on('city_districts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		
		Schema::table('projects', function (Blueprint $table)
        {
            $table->dropForeign('projects_city_district_id_foreign');
        });	
		
        Schema::drop('projects');
    }
}
