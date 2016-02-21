<?php

namespace Monitor\src\Projects\Scrapers;

use Monitor\src\Projects\ProjectScraperAbstract;
use Monitor\Project;
use Htmldom;
use Log;

class PetrzalkaProjectScraper extends ProjectScraperAbstract
{

	protected $city_district = 'Petržalka';
	//only for Petrzalka
	protected $data = array();

	protected function getAllProjects()
	{
		$projects = $this->web->find('.content_body h3');

		return $projects;
	}

	public function getData($project)
	{
		$detailAnchor = $project->getElementByTagName('a');
		$detailsUrl = $detailAnchor->getAttribute('href');
		$projectDetails = $this->getProjectDetails($detailsUrl);

		$data['project'] = [
			'title' => $detailAnchor->plaintext
		];

//		if (!$this->isProjectNew($project)) {
//			continue;
//		}

		$rows = $projectDetails->getElementByTagName('table')->find('tr');

		$proceeding_info_types = [
			'Žiadateľ o územné rozhodnutie',
			'Žiadosť o územné rozhodnutie podaná',
			'Územné rozhodnutie vydané',
			'Žiadosť o stavebné povolenie podaná',
			'Stavebné povolenie vydané',
			'Kolaudačné rozhodnutie'
		];

		$i = 0;
		$proceedings = [];
		$lastType = '';

		//rows
		foreach ($rows as $row){

			if ($th = $row->find('th', 0)) {
				$th = $th->plaintext;
			}

			if ($tr = $row->find('td', 0)) {
				$tr = $tr->plaintext;
			}

			if (empty(trim($tr))) {
				continue;
			}

			if (isset($th)) {
				$th = trim($th);
				$type_id = array_search($th, $proceeding_info_types);
				$type = $proceeding_info_types[$type_id];
				$lastType = $type;

				$proceedings[$type][] = $tr;
			} else {
				$proceedings[$lastType][] = $tr;
			}
		}

		$data['proceedings'] = $this->getTerritorialProceeding($proceedings);

		dd($data);
		return $data;
	}

	public function getTerritorialProceeding($proceedings)
	{
		$type = 'úzmené konanie';
		$proceedingData = [];

		if (array_key_exists('Žiadateľ o územné rozhodnutie', $proceedings)) {
			$i = 0;
			foreach ($proceedings['Žiadateľ o územné rozhodnutie'] as $app){
				$proceedingData[$i]['applicant'] = $app;
				$i++;
			}
		}
		
		if ( array_key_exists('Žiadosť o územné rozhodnutie podaná', $proceedings)) {
			$i = 0;
			foreach ($proceedings['Žiadosť o územné rozhodnutie podaná'] as $notified){
				preg_match("/(\d{1,2})\s?\.\s?(\d{1,2})\s?\.\s?(\d{4})/", $notified, $matches);
				$date = $matches[1]. '.' . $matches[2] . '.' . $matches[3];
				
				$proceedingData[$i]['notified_at'] = \DateTime::createFromFormat('d.m.Y', $date)->format('Y-m-d');
				$i++;
			}
		}
		
		if ( array_key_exists('Územné rozhodnutie vydané', $proceedings)) {
			$i = 0;
			foreach ($proceedings['Územné rozhodnutie vydané'] as $decided_at){
				preg_match("/(\d{1,2})\s?\.\s?(\d{1,2})\s?\.\s?(\d{4})/", $decided_at, $matches);
				$date = $matches[1]. '.' . $matches[2] . '.' . $matches[3];
				
				$proceedingData[$i]['decided_at'] = \DateTime::createFromFormat('d.m.Y', $date)->format('Y-m-d');
								
				if(preg_match("/([\p{L}0-9]+[-]?[\p{L}0-9]+)+(\/([\p{L}0-9]*[-]?[\p{L}0-9]+)+)+/u", $decided_at, $matches)){
						$proceedingData[$i]['file_reference'] = $matches[0];
				}
				
				$i++;
			}
		}
		
		// add to proceeding types to all proceedings
		array_walk ($proceedingData, function(&$a) use ($type) {
			return $a['proceeding_type'] = $type;
		});
		
		return $proceedingData;
	}

	protected function getBuildingProceeding($proceedings)
	{
		
	}

	protected function getProjectDetails($detailsUrl)
	{
		$detailsHtml = new Htmldom($detailsUrl);
		$projectDetails = $detailsHtml->getElementById('content');

		return $projectDetails;
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

	protected function getTitle($project)
	{
		return $project->find('.entry p em', 1)->plaintext;
	}

	protected function getDescription($project)
	{
		return $project->getElementsByTagName('tr', 2)->find('td', 1)->plaintext;
	}

	protected function getAplicant($project)
	{
		return $project->find('.table_big_content')->find('tr', 1)->find('td', 1)->plaintext;
	}

	protected function getProceedingAplicant($project)
	{
		$this->getAplicant($project);
	}

	protected function getProceedingDescription($project)
	{
		return $this->getDescription($project);
	}
}
