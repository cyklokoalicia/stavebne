<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class ZahorskaBystricaProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Záhorská Bystrica';

	protected function getAllProjects()
	{
		$projects = $this->web->find('article');

		//remove first hidden article
		unset($projects[0]);

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
			'posted_at' => $this->getPostDate($project),
			'url' => $this->url,
			'files' => [
				'url' => $this->getFile($project)
			]
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
		$proceedingPostDate = $this->getPostDate($project);
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
		return trim($project->find('h2', 0)->plaintext);
	}

	protected function getProceedingDescription($project)
	{
		return $this->getProceedingTitle($project);
	}

	protected function getPostDate($project)
	{
		$postDate = $project->find('.entry-date', 0)->plaintext;
		if (!strlen(trim($postDate))) {
			return null;
		}

		$postDate = \DateTime::createFromFormat('d.m.Y', trim($postDate))->format('Y-m-d');

		return $postDate;
	}

	protected function getFile($project)
	{
		$proceedingUrl = $project->getElementByTagName('a')->href;	
		$detailsHtml = new Htmldom($proceedingUrl);
		
		$anchor = $detailsHtml->find('.art-content article p a', 0);
		
		if (!strlen(trim($anchor))) {
			return null;
		}
		
		return $anchor->href;
	}
}
