<?php

namespace Monitor\src\Files;

use Monitor\File;
use Monitor\Parcel;
use Monitor\OwnershipList;

class FileAnalyzer
{

	public function analyze()
	{
		$donwloadedFiles = File::where('file_process_id', '=', 2)->get();

		foreach ($donwloadedFiles as $file){
			$txtFilePath = config('monitor.text_file_path') . $file->id . '.txt';
			$text = file_get_contents($txtFilePath);

			$parcelsNums = $this->findParcelNumbers($text);
			
			if ($parcelsNums) {
				$parcels = $this->createDbDataArray($parcelsNums, $file->id);
				Parcel::insert($parcels);
			}

			$listNums = $this->findOwnershipListNumbers($text);
			
			if ($listNums) {
				
				$ownershipLists = $this->createDbDataArray($listNums, $file->id);				
				OwnershipList::insert($ownershipLists);
			}
		}
	}

	protected function findParcelNumbers($text)
	{
		$pattern = '/\W(pozemok|pozemk|pare|parc|par\.|p\. *č\.).{0,20}?[^0-9][1-9][0-9]*(\/[1-9][0-9]*[^0-9])?([, \/0-9])*/';
		$match = preg_match_all($pattern, $text, $wordsArray);
		
		if (!$match) {
			return false;
		}

		//loop over full match and filter array index if value containy word "fond"
		$properlyWords = array_filter($wordsArray[0], function($words)
		{
			return stripos($words, 'fond') !== false ? false : true;
		});

		$parcelNubers = [];

		foreach ($properlyWords as $worlds){
			$match = preg_match_all('/[0-9]+\/[0-9]+|[1-9][0-9]*/', $worlds, $numbers);
			
			if (!$match) {
				continue;
			}
			
			$parcelNubers = array_merge($parcelNubers, $numbers[0]);
		}

		return $parcelNubers;
	}

	protected function findOwnershipListNumbers($text)
	{
		$pattern = '/(LV|list.{0,10}vlastn).{1,10}[^0-9][1-9]([0-9 ,a])*/';
		$match = preg_match_all($pattern, $text, $wordsArray);

		$listNubers = [];
				
		if (!$match) {
			return false;
		}
		
		foreach ($wordsArray[0] as $worlds){
			$match = preg_match_all('/[0-9]{2,}/', $worlds, $numbers);

			if (!$match) {
				continue;
			}

			$listNubers = array_merge($listNubers, $numbers[0]);
		}

		return $listNubers;
	}

	protected function createDbDataArray($parcelsNums, $file_id)
	{
		$dataParcels = [];

		foreach ($parcelsNums as $num){
			$dataParcels[] = [
				'number' => $num,
				'file_id' => $file_id
			];
		}

		return $dataParcels;
	}
	
}
