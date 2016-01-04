<?php

namespace Monitor\src\Projects;

use Monitor\src\Projects\RuzinovProjectScraper;

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
			default:
				break;
		}

		return $this->scraper->scrapeNew();
	}
}
