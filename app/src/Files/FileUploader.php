<?php

namespace Monitor\src\Files;

class FileUploader
{

	public function upload($remoteFileUrl, $newFileName)
	{
		$remoteFile = file_get_contents($remoteFileUrl);
		
		file_put_contents(config('monitor.file_path') . $newFileName, $remoteFile);
	}
}
