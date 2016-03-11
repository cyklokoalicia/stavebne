<?php

namespace Monitor;

use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
	protected $fillable = [
		'number',
		'ownersip_list_number',
		'gps_lat',
		'gps_lon',
		'cadastere_id',
		'file_id'];

}
