<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Monitor\CityDistrict;
use Htmldom;

class VajnoryProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Vajnory';

	protected function getAllProjects()
	{
		$projects = $this->web->getElementById('contentHolder')->children();
		unset($projects[0]); //remove empty block
		array_pop($projects);  //remove pagination

		return $projects;
	}

	protected function getData($project)
	{
		$detailsUrl = $project->getElementByTagName('a')->getAttribute('href');
		$proceedingUrl = $this->domain . '/' . htmlspecialchars_decode($detailsUrl);

		$detailsHtml = new Htmldom($proceedingUrl);
		$projectDetails = $detailsHtml->getElementById('contentHolder');

		if (!$this->isBuildingInfo($projectDetails)) {
			return false;
		}

		if (!$this->isProjectNew($project)) {
			return false;
		}

		$data['proceedings'] = [
			'title' => $this->getProceedingTitle($projectDetails),
			'description' => $this->getProceedingDescription($projectDetails),
			'posted_at' => $this->getPostDate($projectDetails),
			'url' => $proceedingUrl,
			'files' => $this->getFile($projectDetails)
		];

		return $data;
	}

	protected function isBuildingInfo($project)
	{
		$text = $this->getProceedingTitle($project) . ' ' . $this->getProceedingDescription($project);

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
			['stavb', 'oznám'],
			['stavb', 'rozhod'],
			['stavb', 'zmena'],
			['stavb', 'povolen']
		];

		foreach ($doubleExpressions as $expression){
			if ((!(stripos($text, $expression[0])) === false) && (!(stripos($text, $expression[1])) === false)) {
				return true;
			}
		}

		return false;
	}

	protected function isProjectNew($project)
	{
		$proceedingTitle = $this->getProceedingTitle($project);
		
		$city_district_id = CityDistrict::where('name', '=', $this->city_district)->first()->id;

		$proceeding = Project::where('city_district_id', '=', $city_district_id)->whereHas('proceedings', function($q) use ($proceedingTitle)
			{
				$q->where('title', '=', $proceedingTitle);
			})->get()->count();

		return $proceeding ? false : true;
	}

	protected function getProceedingTitle($project)
	{
		return trim($project->getElementByTagName('h1')->plaintext);
	}

	protected function getProceedingDescription($project)
	{
		return $project->find('div', 2)->plaintext;
	}

	protected function getPostDate($project)
	{
		$post = $project->find('.date', 0)->plaintext;
		return $post;
	}

	protected function getFile($project)
	{
		$links = $project->find('a');
		array_pop($links);  //remove back link

		$files = [];
		foreach ($links as $link){
			$files[] = [
				'url' => $this->domain . $link->href
			];
		}

		return $files;
	}
}
