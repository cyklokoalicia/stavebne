<?php

use Illuminate\Database\Seeder;

class DistrictsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('districts')->delete();
        
		\DB::table('districts')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'Bratislava I',
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'Bratislava II',
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'Bratislava III',
			),
			3 => 
			array (
				'id' => 4,
				'name' => 'Bratislava IV',
			),
			4 => 
			array (
				'id' => 5,
				'name' => 'Bratislava V',
			),
		));
	}

}
