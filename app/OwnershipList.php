<?php

namespace Monitor;

use Illuminate\Database\Eloquent\Model;

class OwnershipList extends Model
{
	protected $fillable = [
		'number',
		'file_id'];

}
