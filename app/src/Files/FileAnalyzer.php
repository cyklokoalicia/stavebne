<?php

namespace Monitor\src\Files;

use Monitor\File;
use DB;

class FileAnalyzer
{

	public function analyze()
	{
		$analyzedFiles = File::where('file_process_id', '=', 4)->get();

		foreach ($analyzedFiles as $file){
			$this->updateParcelLocation($file->id);
		}
	}

	public function updateParcelLocation($file_id)
	{
		$s= DB::update('UPDATE parcels JOIN (
						SELECT 
							parcels.number,
							parcels.file_id,
							parcel_locations.ownership_list_number,
							parcel_locations.gps_lat,
							parcel_locations.gps_lon,
							STD(parcel_locations.gps_lat) AS dev1,
							STD(parcel_locations.gps_lon) AS dev2
						FROM parcels 
							JOIN parcel_locations ON parcels.number = parcel_locations.name 
							JOIN ownership_lists ON ownership_lists.file_id = parcels.file_id 
						WHERE 
							parcel_locations.ownership_list_number = ownership_lists.number 
							AND parcels.file_id = ' . $file_id . '
						GROUP BY parcels.number, parcels.ownership_list_number 
						HAVING	dev1 < 0.0001
					) AS P 
						ON parcels.number = P.number 
					SET parcels.gps_lat = P.gps_lat,
						parcels.gps_lon = P.gps_lon
					WHERE
						parcels.file_id = P.file_id
						AND parcels.file_id = ' . $file_id . '
						AND parcels.gps_lat IS null');
		
			dd($s);
	}
	

}
