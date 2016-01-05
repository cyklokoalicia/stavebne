<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class RuzinovProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Ružinov';
	protected $url = 'http://www.ruzinov.sk/sk/uradna-tabula/index/tag:stavebny-urad/status:active';

	protected function getProjects()
	{
		$html = new Htmldom($this->url);
		$projects = $html->find('table.officeboard-doc-listing tr');

		return $projects;
	}

	protected function getConcrateProject($projectArea)
	{
		$link = $projectArea->getElementByTagName('a')->getAttribute('href');

		$html = new Htmldom($this->domain . $link);
		$project = $html->getElementById('main');

		return $project;
	}

	protected function isProjectNew($project)
	{
		$proceedingPostDate = $this->getProceedingPostDate($project);
		$proceedingFileReference = $this->getProceedingFileReference($project);

		$proceeding = Project::whereHas('proceedings', function($q) use ($proceedingFileReference, $proceedingPostDate)
			{
				$q->where('posted_at', '=', $proceedingPostDate)->where('file_reference', '=', $proceedingFileReference);
			})->get()->count();

		return $proceeding ? false : true;
	}

	protected function getProceedingTitle($project)
	{
		return $project->getElementByTagName('h1')->plaintext;
	}

	protected function getProceedingDescription($project)
	{
		$p = $project->getElementsByTagName('tr', 2)->find('td', 0)->plaintext;

		if (stripos($p, 'Popis') === false) {
			return null;
		}

		return $project->getElementsByTagName('tr', 2)->find('td', 1)->plaintext;
	}

	protected function getProceedingFileReference($project)
	{
		return $project->getElementsByTagName('tr', 1)->find('td', 1)->plaintext;
	}

	protected function getProceedingPostDate($project)
	{
		$date = $project->getElementsByTagName('tr', 0)->find('td', 1)->plaintext;
		$postDate = explode('&ndash;', $date);
		$postDate = \DateTime::createFromFormat('d.m.Y', trim($postDate[0]));

		return $postDate->format('Y-m-d');
	}

	protected function getProceedingDropDate($project)
	{
		$date = $project->getElementsByTagName('tr', 0)->find('td', 1)->plaintext;
		$dropDate = explode('&ndash;', $date);
		$dropDate = str_replace("</em>", "", $dropDate[1]); // bug, remove extra </em> tag
		$dropDate = \DateTime::createFromFormat('d.m.Y', trim($dropDate));

		return $dropDate->format('Y-m-d');
	}

	protected function getFileUrl($project)
	{
		$rows = $fileRow = $project->getElementsByTagName('tr');

		foreach ($rows as $row){
			$p = $row->find('td', 0)->plaintext;

			if (stripos($p, 'Prílohy') !== false) {
				$link =  $row->find('td', 1)->getElementByTagName('a')->getAttribute('href');
				
				return $this->domain . $link;
			}
		}

		return null;
	}

	protected function getFileCaption($project)
	{
		
		$rows = $fileRow = $project->getElementsByTagName('tr');

		foreach ($rows as $row){
			$p = $row->find('td', 0)->plaintext;

			if (stripos($p, 'Prílohy') !== false) {
				$caption = $row->find('td', 1)->getElementByTagName('a')->getAttribute('title');
				
				return $caption;
			}
		}

		return null;
	}
}
