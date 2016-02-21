<?php

namespace Monitor\src\Proceedings;

use Monitor\Proceeding;

class ProceedingStoreCommand
{
	public function store($data)
	{
		return Proceeding::create($data);
	}
}
