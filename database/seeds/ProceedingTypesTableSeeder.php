<?php

use Illuminate\Database\Seeder;

class ProceedingTypesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('proceeding_types')->delete();
        
		\DB::table('proceeding_types')->insert(array (
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
		));
	}

}
