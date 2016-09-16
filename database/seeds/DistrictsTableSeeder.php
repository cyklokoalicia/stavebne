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
#Districts (okresy) - according to REGPJ2015        
		\DB::table('districts')->insert(array (
			0 => 
			array (
				'id' => 101,
				'name' => 'Bratislava I',
			),
			1 => 
			array (
				'id' => 102,
				'name' => 'Bratislava II',
			),
			2 => 
			array (
				'id' => 103,
				'name' => 'Bratislava III',
			),
			3 => 
			array (
				'id' => 104,
				'name' => 'Bratislava IV',
			),
			4 => 
			array (
				'id' => 105,
				'name' => 'Bratislava V',
			),
		));
	}

}
