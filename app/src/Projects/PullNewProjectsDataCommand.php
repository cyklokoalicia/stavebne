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
		$RuzinovProjects = $this->projectScraper->scrapeNew('Ružinov');
		$StareMestoProjects = $this->projectScraper->scrapeNew('Staré Mesto');
	//	$PetrzalkaProjects = $this->projectScraper->scrapeNew('Petržalka');
		$VrakunaProjects = $this->projectScraper->scrapeNew('Vrakuňa');
		$DubravkaProjects = $this->projectScraper->scrapeNew('Dúbravka');
		$RusoveProjects = $this->projectScraper->scrapeNew('Rusovce');
		$ZahorskaBystricaProjects = $this->projectScraper->scrapeNew('Záhorská Bystrica');
		$CunovoProjects = $this->projectScraper->scrapeNew('Čunovo');
		$DevinskaNovaVesProjects = $this->projectScraper->scrapeNew('Devínska Nová Ves');
		$RacaProjects = $this->projectScraper->scrapeNew('Rača');
		$VajnoryProjects = $this->projectScraper->scrapeNew('Vajnory');
		$NoveMestoProjects = $this->projectScraper->scrapeNew('Nové Mesto');
		$KarlovaVesProjects = $this->projectScraper->scrapeNew('Karlova Ves');
//		
		
		return 'ok';
	}
}
