<?php

use Illuminate\Database\Seeder;

class ProceedingPhasesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('proceeding_phases')->delete();
        
		\DB::table('proceeding_phases')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'oznámenie',
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'rozhodnutie',
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'odvolanie',
			),
			3 => 
			array (
				'id' => 4,
				'name' => 'odstránenie',
			),
			4 => 
			array (
				'id' => 5,
				'name' => 'nie je známa',
			),
		));
	}

}
