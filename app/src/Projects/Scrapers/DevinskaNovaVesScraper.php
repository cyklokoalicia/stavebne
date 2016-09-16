<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class DevinskaNovaVesScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Devínska Nová Ves';

	protected function getAllProjects()
	{
		$projects = $this->web->find('table tr');
		unset($projects[0]); //remove tr from thead

		return $projects;
	}

	protected function getData($project)
	{
		if (!$this->isProjectNew($project)) {
			return false;
		}
		
		$data['proceedings'] = [
			'title' => $this->getProceedingTitle($project),
			'description' => $this->getProceedingDescription($project),
			'posted_at' => $this->getProceedingPostDate($project),
			'url' => $this->url,
			'files' => $this->getFile($project)
		];
				
		return $data;
	}

	protected function isProjectNew($project)
	{
		$proceedingPostDate = $this->getProceedingPostDate($project);
		$proceedingTitle = $this->getProceedingTitle($project);

		$proceeding = Project::where('city_district_id', '=', $this->city_district_id)
				->whereHas('proceedings', function($q) use ($proceedingPostDate, $proceedingTitle)
				{
					$q->where('posted_at', '=', $proceedingPostDate)->where('title', '=', $proceedingTitle);
				})->get()->count();

		return $proceeding ? false : true;
	}

	protected function getProceedingTitle($project)
	{
		return trim($project->getElementByTagName('a')->plaintext);
	}

	protected function getProceedingDescription($project)
	{
		return $this->getProceedingTitle($project);
	}

	protected function getProceedingPostDate($project)
	{
		$tmp = $project->find('td', 1);
		if( empty($tmp) )
		{
			return null;
		}
		return trim($tmp->plaintext);
	}

	protected function getFile($project)
	{
		return [
			'url' => $project->getElementByTagName('a')->href
		];
	}
}
