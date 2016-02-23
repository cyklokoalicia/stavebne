<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class KarlovaVesProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Karlova Ves';
	
	protected function setUrl()
	{
		$this->url = config('monitor.url.Karlova Ves.construction office');
	}

	protected function getAllProjects()
	{
		$projects = $this->web->getElementById('main-content')->find('article');

		return $projects;
	}

	protected function getData($project)
	{
		$proceedingUrl = $project->getElementByTagName('a')->getAttribute('href');
		$detailsHtml = new Htmldom($proceedingUrl);
		$projectDetails = $detailsHtml->getElementByTagName('article');

		if (!$this->isProjectNew($projectDetails)) {
			return false;
		}

		$data['proceedings'] = [
			'title' => $this->getProceedingTitle($projectDetails),
			'description' => $this->getProceedingDescription($projectDetails),
			'posted_at' => $this->getProceedingPostDate($projectDetails),
			'url' => $proceedingUrl,
			'files' => $this->getFile($projectDetails)
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
		return $project->getElementByTagName('h1')->plaintext;
	}

	protected function getProceedingDescription($project)
	{
		return $project->find('.entry-content',0)->plaintext;
	}

	protected function getProceedingPostDate($project)
	{
		$date = $project->find('time', 0)->datetime;
		$date = preg_match("/\d{4}-\d{2}-\d{2}/", $date, $d);

		return $d[0];
	}

	protected function getFile($project)
	{
		$file = $project->getElementByTagName('a')->href;
		return ['url' => $file];
	}
}
