<?php

namespace Monitor;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
		'title',
		'description',
		'aplicant',
		'gps_lon',
		'gps_lat',
		'posted_at',
		'droped_at',
		'city_district_id'];
	
	//relations
	
	public function proceedings()
    {
        return $this->hasMany('Monitor\Proceeding');
    }
}
