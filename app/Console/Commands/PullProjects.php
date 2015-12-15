<?php

namespace Monitor\Console\Commands;

use Illuminate\Console\Command;

class PullProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:pull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull data of projects from the all web pages of city districts' ;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
