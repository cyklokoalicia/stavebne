<?php

namespace Monitor\src\Projects;

use Monitor\CityDistrict;
use Monitor\File;
use Monitor\ProceedingType;
use Monitor\ProceedingPhase;
use Monitor\src\Files\FileUploader;
use Monitor\src\Proceedings\ProceedingStoreCommand;
use Monitor\src\Projects\ProjectStoreCommand;
use Htmldom;

abstract class ProjectScraperAbstract
{

	//you have to set city district name	
	protected $city_district;
	protected $city_district_id;
	//acces to web html
	public $web;
	//set url or get from config file using city district name
	protected $url;
	protected $domain;
	//you have to set project data
	protected $data;
	protected $projectStorer;
	protected $proceedingStorer;
	protected $uploader;

	abstract protected function getAllProjects();

	abstract protected function isProjectNew($project);

	abstract protected function getData($project);

	protected function setObjects()
	{
		$this->proceedingStorer = new ProceedingStoreCommand();
		$this->projectStorer = new ProjectStoreCommand();
		$this->uploader = new FileUploader();
	}

	protected function setUrl()
	{
		$this->url = config('monitor.url.' . $this->city_district);

		return $this->url;
	}

	protected function setCityDistrictId()
	{
		return $this->city_district_id = CityDistrict::where('name', '=', $this->city_district)->first()->id;
	}

	//get domain name with scheme (e.g html://example.com)
	protected function getDomainName($url)
	{
		$url = parse_url($url);
		return $url['scheme'] . '://' . $url['host'];
	}

	protected function existUrl($url)
	{
		$ua = 'Mozilla/5.0 (Windows NT 5.1; rv:16.0) Gecko/20100101 Firefox/16.0 (ROBOT)';

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);

		$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		return $httpStatus < 400 ? true : false;
	}

	protected function openWeb($url)
	{
		if (!$this->existUrl($url)) {
			return false;
		};

		$htmlDom = new Htmldom($url);

		return $htmlDom;
	}

	protected function trimData($data)
	{
		array_walk_recursive($data, function(&$value, $key)
		{
			$value = is_string($value) ? trim($value) : $value;
		});

		return $data;
	}

	public function scrape()
	{
		$this->setObjects();
		$this->setUrl();
		$this->setCityDistrictId();
		$this->domain = $this->getDomainName($this->url);
		$this->web = $this->openWeb($this->url);

		$projects = $this->getAllProjects();

		if (empty($projects)) {
				throw new \Monitor\src\Projects\EmptyProjectsException('On web->' . $this->url. ' for city district ' . $this->city_district);
		}

		$newData = [];

		//from old to new
		$projects = array_reverse($projects);

		foreach ($projects as $project){

			$data = $this->getData($project);

			if ($data == false || $data == null || empty($data)) {
				continue;
			}

			$data = $this->trimData($data);
			$newData[] = $this->saveData($data);
		}

		return $newData;
	}
	/*
	 * Functions for no repeating same logic in all children
	 */

	protected function saveData($data)
	{
		$savedData = [];

		$project = isset($data['project']) ? $data['project'] : array();

		$savedData['project'] = $project = $this->saveProject($project);

		if (is_array(current($data['proceedings']))) {
			foreach ($data['proceedings'] as $proceeding){
				$savedData['proceeding'][] = $this->saveProceeding($project->id, $proceeding);
			}
		} else {
			$savedData['proceeding'][] = $this->saveProceeding($project->id, $data['proceedings']);
		}
	}

	protected function saveProject($project)
	{
		$project['city_district_id'] = $this->city_district_id;
		$project = $this->projectStorer->store($project);

		return $project;
	}

	protected function getCurlInfo($url)
	{
		$ua = 'Mozilla/5.0 (Windows NT 5.1; rv:16.0) Gecko/20100101 Firefox/16.0 (ROBOT)';

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);

		return curl_getinfo($ch);
	}

	protected function saveFiles($proceeding_id, $files)
	{
		if (empty($files)) {
			return null;
		}

		$files = is_array(current($files)) ? $files : array($files);

		foreach ($files as $file){
			$encodedUrl = urldecode($file['url']);
			$encodedUrl = rawurlencode($encodedUrl);
			$encodedUrl = str_replace("%2F", "/", $encodedUrl);
			$encodedUrl = str_replace("%3A", ":", $encodedUrl);

			if (!$this->existUrl($encodedUrl)) {
				continue;
			}

			$curlInfo = $this->getCurlInfo($encodedUrl);
			$info = pathinfo($file['url']);

			$file_data = [
				'original_filename' => $info['basename'],
				'url' => $encodedUrl,
				'file_size' => $curlInfo['download_content_length'],
				'mime_type' => $curlInfo['content_type'],
				'file_extension' => $info['extension'],
				'file_process_id' => 1,//saved
				'proceeding_id' => $proceeding_id
			];

			$file_data['caption'] = isset($file['caption']) ? $file['caption'] : $file_data['original_filename'];
			$newFile = File::create($file_data);
			$file_name = $newFile->id . '.' . $file_data['file_extension'];

			$this->uploader->upload($encodedUrl, $file_name);
			
			$newFile->update(['file_process_id' => 2]); //downloaded

		}
	}

	protected function saveProceeding($project_id, $proceeding)
	{
		$stringsArray = [];

		$proceeding['project_id'] = $project_id;

		if (isset($proceeding['title'])) {
			$stringsArray[] = $proceeding['title'];
		}

		if (isset($proceeding['description'])) {
			$stringsArray[] = $proceeding['description'];
		}

		if (!(isset($proceeding['file_reference']))) {
			$proceeding['file_reference'] = $this->getProceedingFileReferenceFromStrings($stringsArray);
		}

		if (!isset($proceeding['proceeding_type'])) {
			$proceedingType = $this->getProceedingType($stringsArray);
			$proceeding['proceeding_type_id'] = ProceedingType::where('name', '=', $proceedingType)->first()->id;
			$proceeding['building_change'] = $this->isBuildingChange($proceedingType, $stringsArray);
		}

		if (!isset($proceeding['proceeding_phase'])) {
			$proceedingPhase = $this->getProceedingPhase($stringsArray);
			$proceeding['proceeding_phase_id'] = ProceedingPhase::where('name', '=', $proceedingPhase)->first()->id;
		}

		$newProceeding = $this->proceedingStorer->store($proceeding);

		if (isset($proceeding['files'])) {
			$this->saveFiles($newProceeding->id, $proceeding['files']);
		}

		return $newProceeding;
	}

	//detect in all strings if proceeding is build change ( title, description)
	protected function isBuildingChange($proceedingType, array $arrayStrings = [])
	{
		$change = false;

		if ($proceedingType == 'stavebné konanie') {
			foreach ($arrayStrings as $string){
				if (stripos($string, 'zmen') !== false) {
					$change = true;

					break;
				}
			}
		}

		return $change;
	}

	//search file reference in all strings ( title, description)
	protected function getProceedingFileReferenceFromStrings(array $arrayStrings)
	{
		foreach ($arrayStrings as $string){
			if (preg_match("/([\p{L}0-9][-]?)+(\/([\p{L}0-9][-]?)+){3,}/u", $string, $matches)) {
				$file_reference = $matches[0];
			}
		}

		return isset($file_reference) ? $file_reference : null;
	}

	//search proceeding type in all strings ( title, description)
	protected function getProceedingType(array $arrayStrings)
	{
		$proceeding_types = [
			'územné konanie' => 'územn',
			'kolaudačné konanie' => 'kolaud',
			'stavebné konanie' => 'staveb',
		];

		$matched = false;

		foreach ($proceeding_types as $proceeding => $expression){
			foreach ($arrayStrings as $string){
				if (stripos($string, $expression) !== false) {
					$matched = $proceeding;
					break 2;
				}
			}
		}

		return $matched ? $matched : 'stavebné konanie';
	}

	//search proceeding phase in all given strings ( e.g. title, description)
	protected function getProceedingPhase(array $arrayStrings)
	{
		$proceeding_phases = [
			'oznámenie' => 'oznámenie',
			'ohlásenie' => 'oznámenie',
			'rozhodnutie' => 'rozhodnutie',
			'povolenie' => 'rozhodnutie',
			'odvolani' => 'odvolanie',
			'odstránenie' => 'odstránenie'
		];

		$matched = false;

		foreach ($proceeding_phases as $val => $phase){
			foreach ($arrayStrings as $string){
				if (stripos($string, $val) !== false) {
					$matched = $phase;
					break 2;
				}
			}
		}

		foreach ($arrayStrings as $string){
			if ((stripos($string, 'verejn') !== false) && (stripos($string, 'vyhláš') !== false)) {
				$matched = 'oznámenie';
				break;
			}
		}

		return $matched ? $matched : 'nie je známa';
	}
}
