<?php

namespace Monitor\src\Projects;

use Monitor\CityDistrict;
use Monitor\ProceedingPhase;
use Monitor\ProceedingType;
use Monitor\src\Files\FileUploader;
use Monitor\src\Proceedings\ProceedingStoreCommand;
use Monitor\src\Projects\ProjectScraperStrategy;
use Monitor\src\Projects\ProjectStoreCommand;

class PullNewProjectsDataCommand
{
	protected $fileUploader;
	protected $projectScraper;
	protected $projectStorer;
	protected $proceedingStorer;

	public function __construct(
		ProceedingStoreCommand $proceedingStorer,
		ProjectScraperStrategy $projectScraper,
		ProjectStoreCommand $projectStorer,
		FileUploader $fileUploader)
	{
		$this->fileUploader = $fileUploader;
		$this->proceedingStorer = $proceedingStorer;
		$this->projectScraper = $projectScraper;
		$this->projectStorer = $projectStorer;
	}
	
	public function pull()
	{
//		$RuzinovProjects = $this->projectScraper->scrapeNew('Ružinov');
		$StareMestoProjects = $this->projectScraper->scrapeNew('Staré Mesto');
		
		$projects = array_merge($StareMestoProjects);

		foreach ($projects as $project){
			$project['city_district_id'] = CityDistrict::where('name', '=', $project['city_district'])->first()->id;
			$project['proceeding']['proceeding_phase_id'] = ProceedingPhase::where('name', '=', $project['proceeding']['proceeding_phase'])->first()->id;
			$project['proceeding']['proceeding_type_id'] = ProceedingType::where('name', '=', $project['proceeding']['proceeding_type'])->first()->id;
			
			$storedProject = $this->projectStorer->store($project);
			$project['proceeding']['project_id'] = $storedProject->id;
			$storedProceeding = $this->proceedingStorer->store($project['proceeding']);
					
			if($project['proceeding']['fileUrl'] !== null) {
				$this->fileUploader->upload(
					$project['proceeding']['fileUrl'],
					$project['proceeding']['fileCaption'],
					$storedProceeding->id); 
			}
		}		

		return 'ok';
	}
}
