<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProceedingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proceedings', function (Blueprint $table) {			
            $table->increments('id');
			$table->string('title')->nullable()->default(null);
			$table->text('description')->nullable()->default(null);
			$table->string('file_reference')->nullable()->default(null);
			$table->text('aplicant')->nullable()->default(null);
			$table->decimal('gps_lon', 11, 7)->nullable()->default(null);
			$table->decimal('gps_lat', 11, 7)->nullable()->default(null);
			$table->boolean('building_change')->default(false); 
			$table->date('notified_at')->nullable()->default(null);
			$table->date('decided_at')->nullable()->default(null);
			$table->date('posted_at')->nullable()->default(null);
			$table->date('droped_at')->nullable()->default(null);
			$table->string('url', 512)->collation('ascii_general_ci')->comment('URL to proceeding or Noticeboard');
			$table->integer('project_id')->unsigned();
			$table->integer('proceeding_type_id')->unsigned();
			$table->integer('proceeding_phase_id')->unsigned();
            $table->timestamps();
			$table->softDeletes();
			
			$table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
			$table->foreign('proceeding_type_id')->references('id')->on('proceeding_types')->onDelete('cascade');
			$table->foreign('proceeding_phase_id')->references('id')->on('proceeding_phases')->onDelete('cascade');
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
			$table->dropForeign('proceedings_project_id_foreign');
			$table->dropForeign('proceedings_proceeding_type_id_foreign');
            $table->dropForeign('proceedings_proceeding_phase_id_foreign');
        });	
		
        Schema::drop('proceedings');
    }
}
