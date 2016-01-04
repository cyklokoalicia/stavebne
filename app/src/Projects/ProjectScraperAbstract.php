<?php

namespace Monitor\src\Projects;

abstract class ProjectScraperAbstract
{

	public $domain;
	//must be set
	protected $city_district;
	//must be set
	protected $url;

	// find all projects on web
	abstract protected function getProjects();

	//find concrate project (open him when you need)
	abstract protected function getConcrateProject($projectArea);

	//if project exist in database
	abstract protected function isProjectNew($project);

	//get new projects data
	public function scrapeNew()
	{
		$this->domain = $this->getDomainName($this->url);
		$projects = $this->getProjects();

		$data = [];

		foreach ($projects as $projectArea){

			$project = $this->getConcrateProject($projectArea);

			if (!$this->isProjectNew($project)) {
				continue;
			}

			$data[] = [
				'title' => $this->getTitle($project),
				'description' => $this->getDescription($project),
				'aplicant' => $this->getAplicant($project),
				'posted_at' => $this->getPosteDate($project),
				'droped_at' => $this->getDropeDate($project),
				'city_district' => $this->city_district,
				'proceeding' => [
					'title' => $this->getProceedingTitle($project),
					'description' => $this->getProceedingDescription($project),
					'file_reference' => $this->getProceedingFileReference($project),
					'aplicant' => $this->getProceedingAplicant($project),
					'building_change' => $this->isBuildingChange($project),
					'notified_at' => $this->getProceedingNotificationDate($project),
					'decided_at' => $this->getProceedingDecisionDate($project),
					'posted_at' => $this->getProceedingPostDate($project),
					'droped_at' => $this->getProceedingDropDate($project),
					'proceeding_type' => $this->getProceedingType($project),
					'proceeding_phase' => $this->getProceedingPhase($project),
					'fileUrl' => $this->getFileUrl($project),
					'fileCaption' => $this->getFileCaption($project),
				]
			];
		}

		//change order from oldest projects
		$reversed_data = array_reverse($data);

		return $this->trimData($reversed_data);
	}

	protected function trimData($data)
	{
		array_walk_recursive($data, function(&$value, $key)
		{
			$value = is_string($value) ? trim($value) : $value;
		});

		return $data;
	}

	//get domain name with scheme
	protected function getDomainName($url)
	{
		return parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
	}
	/* 	
	 * Overwrite functions where function return null,only if we know get value
	 */

	//find tile in project and return him
	protected function getTitle($project)
	{
		return null;
	}

	//find tile in project and return him
	protected function getDescription($project)
	{
		return null;
	}

	//find aplicant in project and return him (or null)
	protected function getAplicant($project)
	{
		return null;
	}

	//find post date in project and return him (or null)
	protected function getPosteDate($project)
	{
		return null;
	}

	//find drope date in project and return him (or null)
	protected function getDropeDate($project)
	{
		return null;
	}

	//find proceeding title in project and return him (or null)
	protected function getProceedingTitle($project)
	{
		return null;
	}

	//find proceeding description in project and return him (or null)
	protected function getProceedingDescription($project)
	{
		return null;
	}

	//find proceeding file reference in project and return him (or null)
	protected function getProceedingFileReference($project)
	{
		return null;
	}

	//find proceeding aplicant in project and return him (or null)
	protected function getProceedingAplicant($project)
	{
		return null;
	}

	//find proceeding aplicant in project and return him (or null)
	protected function isBuildingChange($project)
	{
		$type = $this->getProceedingType($project);
		$title = $this->getProceedingTitle($project);
		$description = $this->getProceedingDescription($project);
		
		$matched = false;
		
		if ($type == 'stavebné konanie') {
			if (!(stripos($title, 'zmen') === false)) {
				$matched = true;
			} elseif(!(stripos($description, 'zmen') === false)) {
				$matched = true;
			}
		}
		
		return $matched;
	}

	//find proceeding notify date in project and return him (or null)
	protected function getProceedingNotificationDate($project)
	{
		return null;
	}

	//find proceeding decission date in project and return him (or null)
	protected function getProceedingDecisionDate($project)
	{
		return null;
	}

	//find proceeding post date in project and return him (or null)
	protected function getProceedingPostDate($project)
	{
		return null;
	}

	//find proceeding drop date in project and return him (or null)
	protected function getProceedingDropDate($project)
	{
		return null;
	}

	//find proceeding type in project and return him (or null)
	protected function getProceedingType($project)
	{
		$title = $this->getProceedingTitle($project);
		$description = $this->getProceedingDescription($project);

		$proceeding_types = [
			'územné konanie' => 'územn',
			'kolaudačné konanie' => 'kolaud',
			'stavebné konanie' => 'staveb',
		];

		$matched = null;

		//search in title
		foreach ($proceeding_types as $proceeding => $expression){
			if (!(stripos($title, $expression) === false)) {
				$matched = $proceeding;
				break;
			}
		}

		//search in description if no match in title
		if (!$matched) {
			foreach ($proceeding_types as $type => $expression){
				if (!(stripos($description, $expression) === false)) {
					$matched = $type;
					break;
				}
			}
		}

		return $matched ? $matched : 'stavebné konanie';
	}

	protected function getProceedingPhase($project)
	{
		$title = $this->getProceedingTitle($project);
		$description = $this->getProceedingDescription($project);

		$proceeding_phases = ['oznámenie', 'rozhodnutie', 'odvolanie'];

		$matched = null;

		//search in title
		foreach ($proceeding_phases as $phase){
			if (!(stripos($title, $phase) === false)) {
				$matched = $phase;
				break;
			}
		}

		//search in description if no match in title
		if (!$matched) {
			foreach ($proceeding_phases as $phase){
				if (!(stripos($description, $phase) === false)) {
					$matched = $phase;
					break;
				}
			}
		}

		return $matched ? $matched : 'nie je známa';
	}

	//find file in project and return it´s url (or null)
	protected function getFileUrl($project)
	{
		return null;
	}

	protected function getFileCaption($project)
	{
		return null;
	}
}
