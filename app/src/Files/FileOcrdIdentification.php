<?php

namespace Monitor\src\Files;

use Monitor\File;

class FileOcrdIdentification
{

	public function identify()
	{
		$downloadedFiles = File::where('file_process_id', '=', 2)->get();

		foreach ($downloadedFiles as $file){
			$txtFilePath = config('monitor.text_file_path') . $file->id . '.txt';
			
			if(is_file($txtFilePath)){
				$file->update(['file_process_id' => '3']); //OCRd
			}
			
			clearstatcache();
		}
	}
}
