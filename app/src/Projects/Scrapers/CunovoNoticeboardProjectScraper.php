<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class CunovoNoticeboardProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Čunovo';

	protected function setUrl()
	{
		$this->url = config('monitor.url.Čunovo.noticeboard');
	}

	protected function getAllProjects()
	{
		$projects = $this->web->getElementById('content')->find('article');

		return $projects;
	}

	protected function getData($project)
	{
		$proceedingUrl = $project->find('.button', 0)->getAttribute('href');

		if (!$this->existUrl($proceedingUrl)) {
			return false;
		};

		$detailsHtml = new Htmldom($proceedingUrl);
		$proceedingsDetails = $detailsHtml->getElementById('content');

		if (!$this->isBuildingInfo($proceedingsDetails)) {
			return false;
		}

		if (!$this->isProjectNew($proceedingsDetails)) {
			return false;
		};

		$data['proceedings'] = [
			'title' => $this->getProceedingTitle($proceedingsDetails),
			'description' => $this->getProceedingDescription($proceedingsDetails),
			'posted_at' => $this->getProceedingPostDate($proceedingsDetails),
			'url' => $proceedingUrl,
			'files' => $this->getFiles($proceedingsDetails)
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
		return trim($project->getElementByTagName('h2')->plaintext);
	}

	protected function getProceedingDescription($project)
	{
		$postContent = $project->find('.post-content', 0)->children;

		$description = '';
		$newLine = "\n\r";

		foreach ($postContent as $text){
			$description .= $text->plaintext . $newLine;
		}

		return trim(rtrim($description, $newLine));
	}

	protected function getProceedingPostDate($project)
	{
		$dateText = $project->find('.post-meta time', 0)->getAttribute('datetime');
		if (preg_match("/\d{4}-\d{2}-\d{2}/", $dateText, $postDate)) {
			return $postDate[0];
		}

		return null;
	}

	protected function getFiles($project)
	{
		$anchors = $project->find('.post-content a');
		$files = [];

		foreach ($anchors as $a){
			$files[] = [
				'url' => $a->href,
				'caption' => $a->plaintext
			];
		}

		return $files;
	}
}
