<?php

namespace Monitor\src\Projects;

use Log;

class ProjectScraperContext
{

	protected $scraper;

	public function scrapeNew($city_district)
	{
		switch ($city_district) {
			case 'Ružinov':
				$this->scraper = new Scrapers\RuzinovProjectScraper();
				break;
			case 'Staré Mesto':
				$this->scraper = new Scrapers\StareMestoProjectScraper();
				break;
			case 'Petržalka':
				$this->scraper = new Scrapers\PetrzalkaProjectScraper();
				break;
			case 'Vrakuňa':
				$this->scraper = new Scrapers\VrakunaProjectScraper();
				break;
			case 'Dúbravka':
				$this->scraper = new Scrapers\DubravkaProjectScraper();
				break;
			case 'Rusovce':
				$this->scraper = new Scrapers\RusovceProjectScraper();
				break;
			case 'Záhorská Bystrica':
				$this->scraper = new Scrapers\ZahorskaBystricaProjectScraper();
				break;
			case 'Čunovo':
				$this->scraper[] = 	new Scrapers\CunovoNoticeboardProjectScraper();
				$this->scraper[] = 	new Scrapers\CunovoAnnouncementsProjectScraper();
				break;
			case 'Devínska Nová Ves':
				$this->scraper = new Scrapers\DevinskaNovaVesScraper();
				break;
			case 'Rača':
				$this->scraper = new Scrapers\RacaProjectScraper();
				break;
			case 'Vajnory':
				$this->scraper = new Scrapers\VajnoryProjectScraper();
				break;
			case 'Nové Mesto':
				$this->scraper[] = 	new Scrapers\NoveMestoProjectScraper();
				$this->scraper[] = 	new Scrapers\NoveMestoSpecialsProjectScraper();
				break;
			case 'Karlova Ves':
				$this->scraper[] = new Scrapers\KarlovaVesProjectScraper();
				$this->scraper[] = new Scrapers\KarlovaVesPublicNoticeProjectScraper();
				break;
			default:
				return false;
		}

		$savedData = null;

		if (is_array($this->scraper)) {
			foreach ($this->scraper as $scraper){
				$savedData[] = $this->scrape($scraper);
			}
		} else {
			$savedData = $this->scrape($this->scraper);
		}
		
		$this->scraper = null;

		return $savedData;
	}

	private function scrape($scraper)
	{
		try {
			$savedData = $scraper->scrape();
		} catch (EmptyProjectsException $e) {
			Log::error($e->errorMessage());
			
			return false;
		}

		return $savedData;
	}
}
