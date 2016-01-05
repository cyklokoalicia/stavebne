<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class PetrzalkaProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Petržalka';
	protected $url = 'http://www.petrzalka.sk/uzemne-a-stavebne-konania/';

	public function scrapeNew()
	{
		$this->domain = $this->getDomainName($this->url);
		$projects = $this->getProjects();
		
		$data = [];

		foreach ($projects as $projectArea){

			$project = $this->getConcrateProject($projectArea);

			if (!$this->isProjectNew($project)) {
				continue;
			}
			
					
		dd(getAplicant);
			
			$rows = $project->getElementByTagName('table')->find('tr');

			foreach ($rows as $row){
				$h = $row->find('th', 1)->plaintext;

				switch ($h) {
					case 'Žiadosť o územné rozhodnutie podaná':
						break;

					default:
						break;
				}
			}


			$title = $this->getProceedingTitle($project);
			$description = $this->getProceedingDescription($project);

			$proceeding_types = [
				'územné konanie' => [
					'Žiadosť o územné rozhodnutie podaná',
					'Územné rozhodnutie vydané'
				],
				'kolaudačné konanie' => [
					'Žiadosť o stavebné povolenie podaná',
					'Stavebné povolenie vydané'
				],
				'stavebné konanie' => 'Kolaudačné rozhodnutie',
			];

			$matched = null;

			//search in title
			foreach ($proceeding_types as $proceeding => $expression){
				if (!(stripos($title, $expression) === false)) {
					$matched = $proceeding;
					break;
				}
			}

			foreach ($data as $key => $value){
				
			}


			$data[] = [
				'title' => $this->getTitle($project),
				'description' => $this->getDescription($project),
				'aplicant' => $this->getAplicant($project),
				'posted_at' => $this->getPosteDate($project),
				'droped_at' => $this->getDropeDate($project),
				'city_district' => $this->city_district,
				'proceeding' => [
					'title' => $this->getProceedingTitle($project),
					'description' => $this->getProceedingDescription($project),
					'file_reference' => $this->getProceedingFileReference($project),
					'aplicant' => $this->getProceedingAplicant($project),
					'building_change' => $this->isBuildingChange($project),
					'notified_at' => $this->getProceedingNotificationDate($project),
					'decided_at' => $this->getProceedingDecisionDate($project),
					'posted_at' => $this->getProceedingPostDate($project),
					'droped_at' => $this->getProceedingDropDate($project),
					'proceeding_type' => $this->getProceedingType($project),
					'proceeding_phase' => $this->getProceedingPhase($project),
					'fileUrl' => $this->getFileUrl($project),
					'fileCaption' => $this->getFileCaption($project),
				]
			];
		}

		//change order from oldest projects
		$reversed_data = array_reverse($data);

		return $this->trimData($reversed_data);
	}

	protected function getProjects()
	{
		$html = new Htmldom($this->url);
		$projects = $html->find('.content_body h3');

		return $projects;
	}

	protected function getConcrateProject($project)
	{
		$link = $project->getElementByTagName('a')->getAttribute('href');

		$html = new Htmldom($link);
		$project = $html->getElementById('content');

		return $project;
	}

	protected function isProjectNew($project)
	{
		$title = $this->getProceedingTitle($project);
		$proceedingPostDate = $this->getProceedingPostDate($project);
		$proceedingFileReference = $this->getProceedingFileReference($project);

		$proceeding = Project::whereHas('proceedings', function($q) use ($proceedingFileReference, $proceedingPostDate, $title)
			{
				$q->where('posted_at', '=', $proceedingPostDate)->where('file_reference', '=', $proceedingFileReference)->where('title', '=', $title);
			})->get()->count();

		return $proceeding ? false : true;
	}

	protected function getAplicant($project)
	{
		return $project->find('.table_big_content')->find('tr', 1)->find('td', 1)plaintext;
	}

	protected function getProceedingAplicant($project)
	{
		$this->getAplicant($project);
	}

	protected function getProceedingTitle($project)
	{
		return $project->find('.entry p em', 1)->plaintext;
	}

	protected function getProceedingDescription($project)
	{
		return $project->getElementsByTagName('tr', 2)->find('td', 1)->plaintext;
	}
}
