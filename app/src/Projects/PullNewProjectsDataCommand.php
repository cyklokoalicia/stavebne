<?php

namespace Monitor\src\Projects;

use Monitor\src\Projects\ProjectScraperStrategy;

class PullNewProjectsDataCommand
{
	protected $proceedingStorer;

	public function __construct(ProjectScraperStrategy $projectScraper)
	{
		$this->projectScraper = $projectScraper;
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
