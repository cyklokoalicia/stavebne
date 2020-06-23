<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;

class VrakunaProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Vrakuňa';

	protected function getAllProjects()
	{
		$element=$this->web->getElementById('content');
		if($element == null)
			return null;
		$projects = $element->find('.main-content-page .post');

		return $projects;
	}

	protected function getData($project)
	{
		$proceedingUrl = $project->find('.post-exceprt', 0)->getElementByTagName('a')->getAttribute('href');
		$detailsHtml = new Htmldom($proceedingUrl);

		$projectDetails = $detailsHtml->getElementById('content');

		if (!$this->isProjectNew($projectDetails)) {
			return false;
		}

		$data ['proceedings'] = [
			'title' => $this->getProceedingTitle($projectDetails),
			'description' => $this->getProceedingDescription($projectDetails),
			'posted_at' => $this->getProceedingPostDate($projectDetails),
			'url' => $proceedingUrl,
		];

		if ($files = $this->getFiles($projectDetails)) {
			$data ['proceedings']['files'] = $files;
		}

		return $data;
	}

	protected function isProjectNew($project)
	{
		$proceedingPostDate = $this->getProceedingPostDate($project);
		$proceedingDescription = $this->getProceedingDescription($project);

		$proceeding = Project::where('city_district_id', '=', $this->city_district_id)
				->whereHas('proceedings', function($q) use ($proceedingPostDate, $proceedingDescription)
				{
					$q->where('description', '=', $proceedingDescription);
				})->get()->count();

		return $proceeding ? false : true;
	}

	protected function getProceedingTitle($project)
	{
		return $project->getElementByTagName('h1')->plaintext;
	}

	protected function getProceedingDescription($project)
	{
		$content = $project->find('.post-content', 0)->children;

		$description = '';
		$newLine = "\r\n";

		foreach ($content as $elem){
			$description .= $elem->plaintext . $newLine;
		}

		return trim(rtrim($description, $newLine));
	}

	protected function getProceedingPostDate($project)
	{
		$date = $project->find('.post-side-meta .date', 0);
		$day = $date->find('.day', 0)->plaintext;
		$month = $date->find('.month', 0)->plaintext;

		$monthsArray = [
			'01' => 'Jan',
			'02' => 'Feb',
			'03' => 'Mar',
			'04' => 'Apr',
			'05' => 'Maj',
			'06' => 'Jun',
			'07' => 'Jul',
			'08' => 'Aug',
			'09' => 'Sep',
			'10' => 'Okt',
			'11' => 'Nov',
			'12' => 'Dec',
		];

		$date = date("Y") . '-' . trim($day) . '-' . array_search(trim($month), $monthsArray);

		return $date;
	}

	protected function getFiles($project)
	{
		$files = [];

		//previews
		if ($attachmantImages = $project->find('.attachment-post-blog')) {
			foreach ($attachmantImages as $img){
				$files[] = [
					'url' => $img->src
				];
			}
		}

		if ($attachmants = $project->find('.post-content a')) {
			foreach ($attachmants as $attachmant){
				//detect file
				if (@file_get_contents($attachmant->href, 0, NULL, 0, 1)) {
					$files[] = [
						'url' => $attachmant->href
					];
				}
			}
		}

		return $files ? $files : null;
	}
}
