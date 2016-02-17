<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class RuzinovProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Ružinov';

	protected function getAllProjects()
	{
		$projects = $this->web->find('table.officeboard-doc-listing tr');

		return $projects;
	}

	protected function getData($project)
	{
		$detailsUrl = $project->getElementByTagName('a')->getAttribute('href');
		$proceedingUrl = $this->domain . $detailsUrl;
		$detailsHtml = new Htmldom($proceedingUrl);
		$projectDetails = $detailsHtml->getElementById('main');
		
		if(!$this->isProjectNew($projectDetails)){
			return false;
		};

		$data ['proceedings'] = [
			'title' => $this->getProceedingTitle($projectDetails),
			'description' => $this->getProceedingDescription($projectDetails),
			'file_reference' => $this->getProceedingFileReference($projectDetails),
			'posted_at' => $this->getProceedingPostDate($projectDetails),
			'droped_at' => $this->getProceedingDropDate($projectDetails),
			'url' => $proceedingUrl,
			'files' => [
				'url' => $this->getFileUrl($projectDetails),
				'caption' => $this->getFileCaption($projectDetails)
			]
		];

		return $data;
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
				$link = $row->find('td', 1)->getElementByTagName('a')->getAttribute('href');

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
