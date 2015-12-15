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
				'id' => 1,
				'name' => 'Staré Mesto',
				'district_id' => 1,
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'Podunajské Biskupice',
				'district_id' => 2,
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'Ružinov',
				'district_id' => 2,
			),
			3 => 
			array (
				'id' => 4,
				'name' => 'Vrakuňa',
				'district_id' => 2,
			),
			4 => 
			array (
				'id' => 5,
				'name' => 'Nové Mesto',
				'district_id' => 3,
			),
			5 => 
			array (
				'id' => 6,
				'name' => 'Rača',
				'district_id' => 3,
			),
			6 => 
			array (
				'id' => 7,
				'name' => 'Vajnory',
				'district_id' => 3,
			),
			7 => 
			array (
				'id' => 8,
				'name' => 'Devín',
				'district_id' => 4,
			),
			8 => 
			array (
				'id' => 9,
				'name' => 'Devínska Nová Ves',
				'district_id' => 4,
			),
			9 => 
			array (
				'id' => 10,
				'name' => 'Dúbravka',
				'district_id' => 4,
			),
			10 => 
			array (
				'id' => 11,
				'name' => 'Karlova Ves',
				'district_id' => 4,
			),
			11 => 
			array (
				'id' => 12,
				'name' => 'Lamač',
				'district_id' => 4,
			),
			12 => 
			array (
				'id' => 13,
				'name' => 'Záhorská Bystrica',
				'district_id' => 4,
			),
			13 => 
			array (
				'id' => 14,
				'name' => 'Čunovo',
				'district_id' => 5,
			),
			14 => 
			array (
				'id' => 15,
				'name' => 'Jarovce',
				'district_id' => 5,
			),
			15 => 
			array (
				'id' => 16,
				'name' => 'Petržalka',
				'district_id' => 5,
			),
			16 => 
			array (
				'id' => 17,
				'name' => 'Rusovce',
				'district_id' => 5,
			),
		));
	}

}
