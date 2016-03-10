<?php

namespace Monitor;

use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{	
	protected $fillable = [
		'number',
		'file_id'];

}
