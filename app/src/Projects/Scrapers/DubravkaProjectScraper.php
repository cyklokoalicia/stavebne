<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class DubravkaProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Dúbravka';

	protected function getAllProjects()
	{
		$projects = $this->web->getElementById('category-article')->find('.item');

		return $projects;
	}

	protected function getData($project)
	{
		$proceedingUrl = $project->find('.item-detail', 0)->getElementByTagName('a')->getAttribute('href');
		$detailsHtml = new Htmldom($proceedingUrl);

		$projectDetails = $detailsHtml->getElementById('body');

		if (!$this->isProjectNew($projectDetails)) {
			return false;
		}

		$dates = $this->getDates($projectDetails);

		$data ['proceedings'] = [
			'title' => $this->getProceedingTitle($projectDetails),
			'description' => $this->getProceedingDescription($projectDetails),
			'file_reference' => $this->getFileReference($projectDetails),
			'posted_at' => $dates[0],
			'droped_at' => $dates[1],
			'url' => $proceedingUrl,
			'files' => $this->getFiles($projectDetails)
		];

		return $data;
	}

	protected function isProjectNew($project)
	{		
		$proceedingPostDate = $this->getDates($project)[0];
		$proceedingDescription = $this->getProceedingDescription($project);

		$proceeding = Project::where('city_district_id', '=', $this->city_district_id)
				->whereHas('proceedings', function($q) use ($proceedingPostDate, $proceedingDescription)
				{
					$q->where('posted_at', '=', $proceedingPostDate)->where('description', '=', $proceedingDescription);
				})->get()->count();

		return $proceeding ? false : true;
	}

	protected function getProceedingTitle($project)
	{
		return $project->getElementByTagName('h1')->plaintext;
	}

	protected function getProceedingDescription($project)
	{
		$content = $project->getElementByTagName('h1')->nextSibling()->find('.col-sm-12', 0)->children;

		$description = '';
		$newLine = "\n\r";

		foreach ($content as $elem){
			if ($elem->tag == 'p') {
				$description .= $elem->plaintext . $newLine;
			}
		}

		return trim(rtrim($description, $newLine));
	}

	function getFileReference($project)
	{
		$content = $project->getElementByTagName('h1')->nextSibling()->find('.col-sm-12', 0)->children;

		foreach ($content as $elem){
			if ($elem->tag == 'p') {
				if (preg_match("/([\p{L}0-9][-]?)+(\/([\p{L}0-9][-]?)+){3,}/u", $elem->plaintext, $matches)) {
					$file_reference = $matches[0];
				}
			}
		}

		return isset($file_reference) ? $file_reference : null;
	}

	protected function getDates($project)
	{
		$dates = [null, null];
		$content = $project->getElementByTagName('h1')->nextSibling()->find('.col-sm-12', 0)->children;

		foreach ($content as $elem){
			if ($elem->tag == 'p') {
				if (preg_match_all("/\d{2}\.\d{2}\.\d{4}/", $elem->plaintext, $d)) {
					$dates[] = isset($d[0][0]) ? \DateTime::createFromFormat('d.m.Y', $d[0][0])->format('Y-m-d') : null;
					$dates[] = isset($d[0][1]) ? \DateTime::createFromFormat('d.m.Y', $d[0][1])->format('Y-m-d') : null;
				}
			}
		}

		return $dates;
	}

	protected function getFiles($project)
	{
		$content = $project->getElementByTagName('h1')->nextSibling()->find('.col-sm-12', 0)->children;

		$files = [];

		foreach ($content as $elem){
			if ($elem->tag == 'p') {
				if ($a = $elem->getElementByTagName('a')) {
					$files[] = [
						'url' => $elem->getElementByTagName('a')->href
					];
				}
			}
		}

		return $files ? $files : null;
	}
}
