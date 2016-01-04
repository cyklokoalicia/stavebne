<?php

namespace Monitor\src\Projects;

use Monitor\Project;

class ProjectStoreCommand
{

	public function store($data)
	{
		return Project::create($data);
	}
}
