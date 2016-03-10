<?php

namespace Monitor\Console\Commands;

use Illuminate\Console\Command;
use Monitor\src\Projects\PullNewProjectsDataCommand;
use Monitor\src\Files\FileAnalyzer;

class PullProjects extends Command
{
    protected $signature = 'project:pull';
    protected $description = 'Pull data of projects from the all web pages of city districts' ;
	
	protected $fileAnalyzer;
	protected $projectPuller;

    public function __construct(FileAnalyzer $fileAnalyzer, PullNewProjectsDataCommand $projectPuller)
    {
		$this->fileAnalyzer = $fileAnalyzer;
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
//		$this->projectPuller->pull();
		$this->fileAnalyzer->analyze();
		
		$this->info('ok');
    }
}
