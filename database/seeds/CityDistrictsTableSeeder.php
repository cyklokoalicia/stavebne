<?php

use Illuminate\Database\Seeder;

class CityDistrictsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('city_districts')->delete();
        
		\DB::table('city_districts')->insert(array (
			0 => 
			array (
				'id' => 528595,
				'name' => 'Staré Mesto',
				'district_id' => 101,
			),
			1 => 
			array (
				'id' => 529311,
				'name' => 'Podunajské Biskupice',
				'district_id' => 102,
			),
			2 => 
			array (
				'id' => 529320,
				'name' => 'Ružinov',
				'district_id' => 102,
			),
			3 => 
			array (
				'id' => 529338,
				'name' => 'Vrakuňa',
				'district_id' => 102,
			),
			4 => 
			array (
				'id' => 529346,
				'name' => 'Nové Mesto',
				'district_id' => 103,
			),
			5 => 
			array (
				'id' => 529354,
				'name' => 'Rača',
				'district_id' => 103,
			),
			6 => 
			array (
				'id' => 529362,
				'name' => 'Vajnory',
				'district_id' => 103,
			),
			7 => 
			array (
				'id' => 529401,
				'name' => 'Devín',
				'district_id' => 104,
			),
			8 => 
			array (
				'id' => 529371,
				'name' => 'Devínska Nová Ves',
				'district_id' => 104,
			),
			9 => 
			array (
				'id' => 529389,
				'name' => 'Dúbravka',
				'district_id' => 104,
			),
			10 => 
			array (
				'id' => 529397,
				'name' => 'Karlova Ves',
				'district_id' => 104,
			),
			11 => 
			array (
				'id' => 529419,
				'name' => 'Lamač',
				'district_id' => 104,
			),
			12 => 
			array (
				'id' => 529427,
				'name' => 'Záhorská Bystrica',
				'district_id' => 104,
			),
			13 => 
			array (
				'id' => 529435,
				'name' => 'Čunovo',
				'district_id' => 105,
			),
			14 => 
			array (
				'id' => 529443,
				'name' => 'Jarovce',
				'district_id' => 105,
			),
			15 => 
			array (
				'id' => 529460,
				'name' => 'Petržalka',
				'district_id' => 105,
			),
			16 => 
			array (
				'id' => 529494,
				'name' => 'Rusovce',
				'district_id' => 105,
			),
		));
	}

}
