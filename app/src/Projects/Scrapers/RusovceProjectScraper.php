<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class RusovceProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Rusovce';

	protected function getAllProjects()
	{
		$projects = $this->web->getElementById('middle')->find('a');

		return $projects;
	}

	protected function getData($project)
	{			
		if(!$project->plaintext){
			return false;
		}
		
		if (!$this->isProjectNew($project)) {
			return false;
		}
		
		$data ['proceedings'] = [
			'title' => $this->getProceedingTitle($project),
			'description' => $this->getProceedingDescription($project),
			'url' => $this->url,
			'files' => $this->getFile($project)
		];

		return $data;
	}

	protected function isProjectNew($project)
	{
		$proceedingTitle = $this->getProceedingTitle($project);

		$proceeding = Project::whereHas('proceedings', function($q) use ($proceedingTitle)
			{
				$q->where('title', '=', $proceedingTitle);
			})->get()->count();

		return $proceeding ? false : true;
	}

	protected function getProceedingTitle($project)
	{
		return trim($project->plaintext);
	}

	protected function getProceedingDescription($project)
	{
		return $this->getProceedingTitle($project);
	}

	protected function getFile($project)
	{
		return array('url' => $this->domain . $project->href);
	}
}
