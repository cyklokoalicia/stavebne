<?php

namespace Monitor\Console\Commands;

use Illuminate\Console\Command;
use Monitor\src\Projects\PullNewProjectsDataCommand;
use Monitor\src\Files\FileRecognizer;
use Monitor\src\Files\FileAnalyzer;
use Monitor\src\Files\FileOcrdIdentification;

class PullProjects extends Command
{

	protected $signature = 'project:pull';
	protected $description = 'Pull data of projects from the all web pages of city districts';
	protected $fileAnalyzer;
	protected $fileOcrdIdentification;
	protected $fileRecognizer;
	protected $projectPuller;

	public function __construct(
		FileAnalyzer $fileAnalyzer,
		FileOcrdIdentification $fileOcrdIdentification,
		FileRecognizer $fileRecognizer,
		PullNewProjectsDataCommand $projectPuller)
	{
		$this->fileAnalyzer = $fileAnalyzer;
		$this->fileOcrdIdentification = $fileOcrdIdentification;
		$this->fileRecognizer = $fileRecognizer;
		$this->projectPuller = $projectPuller;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->projectPuller->pull();
		echo "pull ok\n";
		$this->fileOcrdIdentification->identify();
		echo "identification ok\n";
		$this->fileRecognizer->recognize();
		echo "recognification ok\n";	
		$this->fileAnalyzer->analyze();
		echo "analysis ok\n";
		$this->info('ok');
	}
}
