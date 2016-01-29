<?php

use Illuminate\Database\Seeder;

class GpsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        \DB::table('gps')->delete();
	echo("mysql --host ".env('DB_HOST')." -u ".env('DB_USERNAME')." -p ".env('DB_PASSWORD')." ".env('DB_DATABASE'). "<". getcwd().'/database/seeds/gps.sql');
        
    }
}
