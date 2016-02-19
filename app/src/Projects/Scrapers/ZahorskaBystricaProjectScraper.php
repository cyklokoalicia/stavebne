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
		$projects = $this->web->find('.art-article table tr');

		//remove table header
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
			'droped_at' => $this->getDropDate($project),
			'url' => $this->url,
			'files' => [
				'url' => $this->getFile($project)
			]
		];

		return $data;
	}

	protected function isBuildingInfo($project)
	{
		$text = $project->find('td', 0)->plaintext;

		$expressions = [
			'kolaud',
			'staveb',
			'územn'
		];

		foreach ($expressions as $expression){
			if (!(stripos($text, $expression)) === false) {
				return true;
			}
		}

		$doubleExpressions = [
			['stavb', 'oznám'],
			['stavb', 'rozhod'],
			['stavb', 'zmena']
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
		$proceedingPostDate = $this->getPostDate($project);
		$proceedingTitle = $this->getProceedingTitle($project);

		$proceeding = Project::whereHas('proceedings', function($q) use ($proceedingPostDate, $proceedingTitle)
			{
				$q->where('posted_at', '=', $proceedingPostDate)->where('title', '=', $proceedingTitle);
			})->get()->count();

		return $proceeding ? false : true;
	}

	protected function getProceedingTitle($project)
	{
		return trim($project->find('td', 0)->plaintext);
	}

	protected function getProceedingDescription($project)
	{
		return $project->find('td', 0)->innertext;
	}

	protected function getPostDate($project)
	{
		$postDate = $project->find('td', 1)->plaintext;
		if (!strlen(trim($postDate))) {
			return null;
		}

		$postDate = \DateTime::createFromFormat('d.m.Y', trim($postDate))->format('Y-m-d');

		return $postDate;
	}

	protected function getDropDate($project)
	{
		$dropDate = $project->find('td', 2)->plaintext;
		if (preg_match("/\d{2}.\d{2}.\d{4}/", $dropDate, $date)) {
			return \DateTime::createFromFormat('d.m.Y', $date[0])->format('Y-m-d');
		}
		return null;
	}

	protected function getFile($project)
	{
		return $project->getElementByTagName('a')->href;
	}
}