<?php

namespace Monitor\src\Projects;

use Log;

class ProjectScraperStrategy
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
				$this->scraper = new Scrapers\ZahorskaBystricaProjectScraper();
				break;
			default:
				break;
		}
		
		$savedData = '';
		
		try {
			$savedData = $this->scraper->scrape();
		} catch (EmptyProjectsException $e) {
			Log::error($e->errorMessage());
		}

		return $savedData;
	}
}
