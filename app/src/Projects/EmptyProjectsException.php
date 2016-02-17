<?php

namespace Monitor\src\Projects;

class EmptyProjectsException extends \Exception
{
	public function errorMessage()
	{
		$errorMsg = __CLASS__ .' -> Web projects link or web structure has changed:'
			."\n <strong>On line</strong>:". $this->getLine() . ' in ' . $this->getFile()
			."\n </strong>Message:</strong>" . $this->getMessage() . ''; 
		return $errorMsg;
	}
}
