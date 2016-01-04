<?php

namespace Monitor\src\Files;

use Monitor\File;
use Monitor\src\Files\FileInfo;

class FileUploader
{

	public function upload($url, $file_caption, $proceedind_id)
	{	
		$headers = get_headers($url, 1);
		
		$data = [
			'original_filename' => basename($url),
			'file_size' =>  $headers['Content-Length'],
			'caption' => $file_caption,
			'mime_type' => $headers['Content-Type'],
			'file_extension' => FileInfo::mimeTypeToFileExtension($headers['Content-Type']),
			'proceeding_id' => $proceedind_id
		];
		
		$file = File::create($data);		
		$newFileName = config('monitor.file_path') . $file->id . '.' . $file->file_extension;
		$remoteFile = file_get_contents($url);

		file_put_contents($newFileName, $remoteFile);

	}
}
