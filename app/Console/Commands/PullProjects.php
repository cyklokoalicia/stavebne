<?php

namespace Monitor\Console\Commands;

use Illuminate\Console\Command;
use Monitor\src\Projects\PullNewProjectsDataCommand;

class PullProjects extends Command
{
    protected $signature = 'project:pull';
    protected $description = 'Pull data of projects from the all web pages of city districts' ;
	
	protected $projectPuller;

    public function __construct(PullNewProjectsDataCommand $projectPuller)
    {
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
		$this->info($this->projectPuller->pull());
    }
}
