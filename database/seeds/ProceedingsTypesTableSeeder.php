<?php

use Illuminate\Database\Seeder;

class ProceedingsTypesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('proceedings_types')->delete();
        
		\DB::table('proceedings_types')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'stavebné konanie',
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'územné konanie',
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'kolaudačné konanie',
			),
			3 => 
			array (
				'id' => 4,
				'name' => 'zmena',
			),
			4 => 
			array (
				'id' => 5,
				'name' => 'nie je známe',
			),
		));
	}

}
