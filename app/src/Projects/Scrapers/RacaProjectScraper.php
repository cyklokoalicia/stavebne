<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;

class RacaProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Rača';

	protected function getAllProjects()
	{
		$projects = $this->web->find('table tr');
		unset($projects[0]); //remove tr in thead

		return $projects;
	}

	protected function getData($project)
	{
		if (!$this->isBuildingInfo($project)) {
			return false;
		}

		if (!$this->isProjectNew($project)) {
			return false;
		}

		$data ['proceedings'] = [
			'title' => $this->getProceedingTitle($project),
			'description' => $this->getProceedingDescription($project),
			'posted_at' => $this->getProceedingPostDate($project),
			'droped_at' => $this->getProceedingDropDate($project),
			'url' => $this->url,
			'files' => $this->getFile($project)
		];

		return $data;
	}

	protected function isBuildingInfo($project)
	{
		$text = $this->getProceedingTitle($project);

		$expressions = [
			'kolaud',
			'staveb',
			'územn',
			'dialnic'
		];

		foreach ($expressions as $expression){
			if (!(stripos($text, $expression)) === false) {
				return true;
			}
		}

		$doubleExpressions = [
			['stav', 'oznám'],
			['stav', 'rozhod'],
			['stav', 'zmena'],
			['stav', 'povolen']
		];
		
		foreach ($doubleExpressions as $expression){
			if ((stripos($text, $expression[0]) !== false) && (stripos($text, $expression[1]) !== false)) {
				return true;
			}
		}

		return false;
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
		return trim($project->getElementsByTagName('td', 2)->plaintext);
	}

	protected function getProceedingDescription($project)
	{
		return $this->getProceedingTitle($project);
	}

	protected function getProceedingPostDate($project)
	{
		$postDate = trim($project->getElementsByTagName('td', 0)->plaintext);

		return (!empty($postDate)) ? \DateTime::createFromFormat('d.m.Y', $postDate)->format('Y-m-d') : null;
	}

	protected function getProceedingDropDate($project)
	{
		$dropDate = trim($project->getElementsByTagName('td', 1)->plaintext);

		return (!empty($dropDate)) ? \DateTime::createFromFormat('d.m.Y', $dropDate)->format('Y-m-d') : null;
	}

	protected function getFile($project)
	{
		$fileLink = $project->getElementsByTagName('td', 2)->find('a', 0);

		return [
			'url' => $this->domain . $fileLink->href,
			'caption' => $fileLink->title
		];
	}
}
