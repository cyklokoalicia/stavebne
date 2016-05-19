<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class NoveMestoProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Nové Mesto';

	protected function setUrl()
	{
		$this->url = config('monitor.url.Nové Mesto.construction office');
	}

	protected function getAllProjects()
	{
		$projects = $this->web->getElementById('obsah')->find('.uradna-tabula-list tr');
		unset($projects[0]); //remove tr in thead
		
		return $projects;
	}

	protected function getData($project)
	{
		if (!$this->isProjectNew($project)) {
			return false;
		};

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
		return trim($project->getElementsByTagName('td', 0)->plaintext);
	}

	protected function getProceedingDescription($project)
	{
		$title = $this->getProceedingTitle($project);
		
		$contantType = trim($project->getElementsByTagName('td', 2)->plaintext);
		return $title . ' \r\n ' . $contantType;
	}

	protected function getProceedingPostDate($project)
	{
		$dropDate = trim($project->getElementsByTagName('td', 1)->plaintext);

		return (!empty($dropDate)) ? \DateTime::createFromFormat('d.m.Y', $dropDate)->format('Y-m-d') : null;
	}

	protected function getFile($project)
	{
		if(empty($project->getElementsByTagName('td', 3)->find('a', 0))){
			return null;
		}
		
		$href = $project->getElementsByTagName('td', 3)->find('a', 0)->href;

			return ['url' => $this->domain . $href];
	}
}
