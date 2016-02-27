<?php
$scraperStrategy = new \Monitor\src\Projects\ProjectScraperStrategy;

$command = new \Monitor\src\Projects\PullNewProjectsDataCommand($scraperStrategy);
$command->pull();