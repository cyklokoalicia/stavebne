<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call(UserTableSeeder::class);

		Model::reguard();
		$this->call('DistrictsTableSeeder');
		$this->call('CityDistrictsTableSeeder');
		$this->call('ProceedingTypesTableSeeder');
		$this->call('ProceedingPhasesTableSeeder');
		$this->call('FileProcessesTableSeeder');

		exec("mysql --host " . env('DB_HOST') . " -u " . env('DB_USERNAME') . " -p " . env('DB_PASSWORD') . " " . env('DB_DATABASE') . " < " .'"'.getcwd().'/database/sql/parcel_locations.sql'.'"');
	}
}
