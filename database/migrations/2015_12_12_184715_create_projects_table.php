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
			$table->string('tilte');
			$table->string('file_reference');
			$table->text('description');
			$table->text('aplicant');
			$table->date('notified_at');
			$table->date('decided_at');
			$table->date('posted_at');
			$table->date('droped_at');
			$table->integer('city_district_id')->unsigned();
            $table->timestamps();
			$table->softDeletes();
			
			$table->foreign('city_district_id')->references('id')->on('city_districts')->onDelete('cascade');
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
