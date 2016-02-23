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
		$projects = $this->web->getElementById('block-system-main')->find('.views-row');

		return $projects;
	}

	protected function getData($project)
	{
		$detailsUrl = $project->getElementByTagName('a')->getAttribute('href');
		$proceedingUrl = $this->domain . '/' . htmlspecialchars_decode($detailsUrl);

		$detailsHtml = new Htmldom($proceedingUrl);
		$projectDetails = $detailsHtml->getElementById('content');
		
		if (!$this->isBuildingInfo($projectDetails)) {
			return false;
		}

		if (!$this->isProjectNew($projectDetails)) {
			return false;
		}
		
		$data['proceedings'] = [
			'title' => $this->getProceedingTitle($projectDetails),
			'description' => $this->getProceedingDescription($projectDetails),
			'posted_at' => $this->getProceedingPostDate($projectDetails),
			'url' => $proceedingUrl,
			'files' => $this->getFiles($projectDetails)
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
		$proceedingTitle = $this->getProceedingTitle($project);

		$proceeding = Project::where('city_district_id', '=', $this->city_district_id)
				->whereHas('proceedings', function($q) use ($proceedingTitle)
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
		if($des = $project->find('.field-type-text-with-summary',0) ){
			return $des->plaintext;
		}
		
		return $this->getProceedingTitle($project);
	}

	protected function getProceedingPostDate($project)
	{
		return $project->find('.date-display-single', 0)->content;
	}

	protected function getFiles($project)
	{
		$rows= $project->find('.table tr');
		unset($rows[0]); //remove td in thead

		$files = [];
		foreach ($rows as $row){
			$files[] = [
				'url' => $row->find('a',0)->href
			];
		}

		return $files;
	}
}
