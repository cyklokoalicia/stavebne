<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class StareMestoProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Staré Mesto';
	protected $url = 'http://www.staremesto.sk/index.php?rain=de/govis/uradnatabula/utid:1006';

	protected function getProjects()
	{
		$html = new Htmldom($this->url);
		$projects = $html->find('.oznamNaUradnejTabuli');

		return $projects;
	}

	protected function getConcrateProject($project)
	{
		return $project;
	}

	protected function isProjectNew($project)
	{
		$proceedingTitle = $this->getProceedingTitle($project);
		$proceedingPostDate = $this->getProceedingPostDate($project);
		$proceedingFileReference = $this->getProceedingFileReference($project);

		$proceeding = Project::whereHas('proceedings', function($q) use ($proceedingFileReference, $proceedingPostDate,$proceedingTitle)
			{
				$q->where('posted_at', '=', $proceedingPostDate)->where('file_reference', '=', $proceedingFileReference);
			})->get()->count();

		return $proceeding ? false : true;
	}

	protected function getDescription($project)
	{
		return $this->getProceedingDescription($project);
	}

	protected function getProceedingTitle($project)
	{
		return $project->getElementByTagName('h2')->plaintext;
	}

	protected function getProceedingPostDate($project)
	{	
		$dateRow = $project->find('.oznamDatumy', 0)->plaintext;
		$dateText = preg_replace('/\s+/', ' ', $dateRow);
		$dateArray = explode(' ', trim($dateText));
		$postDate = \DateTime::createFromFormat('d.m.Y', trim($dateArray[1]));

		return $postDate->format('Y-m-d');
	}

	protected function getProceedingDropDate($project)
	{
		$dateRow = $project->find('.oznamDatumy', 0)->plaintext;
		$dateText = preg_replace('/\s+/', ' ', $dateRow);
		$dateArray = explode(' ', trim($dateText));
		$dropDate = \DateTime::createFromFormat('d.m.Y', trim($dateArray[3]));

		return $dropDate->format('Y-m-d');
	}

	protected function getProceedingFileReference($project)
	{
		$text = $project->getElementsByTagName('p',0)->plaintext;
		$textArray = preg_split('/\r\n/', $text);
		
		return $textArray[0];
	}

	protected function getProceedingDescription($project)
	{
		$text = $project->getElementsByTagName('p',0)->plaintext;
		$textArray = preg_split('/\r\n/', $text);

		return trim($textArray[1], '"');
	}

	protected function getFileUrl($project)
	{
		return $this->domain . $project->getElementsByTagName('a',0)->getAttribute('href');
	}

	protected function getFileCaption($project)
	{
		return $project->getElementsByTagName('a',0)->plaintext;
	}
}
