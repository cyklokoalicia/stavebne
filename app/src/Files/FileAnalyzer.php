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
			$this->updateFileLocation($file->id);
			$this->updateProceedingLocation($file->id);
			$this->updateProjectLocation($file->id);
			
			$file->update(['file_process_id' => 5]); //analyzed
		}
	}

	public function updateParcelLocation($file_id)
	{
		DB::update('UPDATE parcels JOIN (
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
						GROUP BY 
							parcels.number,
							parcels.ownership_list_number 
						HAVING	dev1 < 0.0001
					) AS P 
						ON parcels.number = P.number 
					SET 
						parcels.gps_lat = P.gps_lat,
						parcels.gps_lon = P.gps_lon
					WHERE
						parcels.file_id = P.file_id
						AND parcels.file_id = ' . $file_id . '
						AND parcels.gps_lat IS null');
	}

	public function updateFileLocation($file_id)
	{
		DB::update('UPDATE files JOIN (
						SELECT 
							COUNT(*),AVG(parcels.gps_lat) AS plat,
							AVG(parcels.gps_lon) AS plon,
							STD(parcels.gps_lat) AS dev1,
							STD(parcels.gps_lon) as dev2,
							parcels.file_id
						FROM parcels 
						WHERE  parcels.file_id = ' . $file_id . '
						GROUP BY parcels.file_id 
						HAVING dev1 <0.001
					) AS X 
						ON files.id = X.file_id 
					SET 
						files.gps_lat = plat,
						files.gps_lon = plon
					WHERE 
						files.gps_lat IS null');
	}

	public function updateProceedingLocation($file_id)
	{
		DB::update('UPDATE proceedings JOIN (
						SELECT 
							COUNT(*),
							AVG(parcels.gps_lat) AS	plat,
							AVG(parcels.gps_lon) AS plon,
							STD(parcels.gps_lat) as dev1,
							STD(parcels.gps_lon) as dev2,
							parcels.file_id,
							proceedings.id
						FROM parcels 
						JOIN files 
							ON parcels.file_id = files.id 
						JOIN proceedings
							ON files.proceeding_id = proceedings.id
						WHERE files.id = ' . $file_id . '
						GROUP BY proceedings.id HAVING dev1 <0.001
					) AS X 
						ON proceedings.id = X.id 
					SET 
						proceedings.gps_lat = plat,
						proceedings.gps_lon = plon
					WHERE 
						proceedings.gps_lat IS null');
	}

	public function updateProjectLocation($file_id)
	{
		DB::update('UPDATE projects JOIN (
						SELECT 
							count(*),
							AVG(parcels.gps_lat) AS	plat,
							AVG(parcels.gps_lon) as plon,
							STD(parcels.gps_lat) as dev1,
							STD(parcels.gps_lon) as dev2,
							parcels.file_id,
							projects.id
						FROM 
							parcels
						JOIN files 
							ON parcels.file_id = files.id 
						JOIN proceedings
							ON files.proceeding_id = proceedings.id
						JOIN projects 
							ON proceedings.project_id = projects.id
						WHERE 
							files.id = ' . $file_id . '
						GROUP BY projects.id
						HAVING dev1 <0.001
					) as X 
						ON projects.id = X.id
					SET
						projects.gps_lat = plat,
						projects.gps_lon = plon
					WHERE 
						projects.gps_lat IS null');
	}
}
