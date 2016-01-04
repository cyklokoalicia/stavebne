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

	protected function getConcrateProject($project)
	{
		$link = $project->getElementByTagName('a')->getAttribute('href');

		$html = new Htmldom($this->domain . $link);
		$project = $html->getElementById('main');

		return $project;
	}

	protected function isProjectNew($project)
	{
		$title = $this->getProceedingTitle($project);
		$proceedingPostDate = $this->getProceedingPostDate($project);
		$proceedingFileReference = $this->getProceedingFileReference($project);

		$proceeding = Project::whereHas('proceedings', function($q) use ($proceedingFileReference, $proceedingPostDate, $title)
			{
				$q->where('posted_at', '=', $proceedingPostDate)->where('file_reference', '=', $proceedingFileReference)->where('title', '=', $title);
			})->get()->count();

		return $proceeding ? false : true;
	}

	protected function getProceedingTitle($project)
	{
		return $project->getElementByTagName('h1')->plaintext;
	}

	protected function getProceedingDescription($project)
	{
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
		if ($fileRow = $project->getElementsByTagName('tr', 3)) {
			$link = $fileRow->find('td', 1)->getElementByTagName('a')->getAttribute('href');

			return $this->domain . $link;
		} else {
			return null;
		}
	}
	
	protected function getFileCaption($project) {
		if ($fileRow = $project->getElementsByTagName('tr', 3)) {
			$caption = $fileRow->find('td', 1)->getElementByTagName('a')->getAttribute('title');

			return $caption;
		} else {
			return null;
		}
	}
}
