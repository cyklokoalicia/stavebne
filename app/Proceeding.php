<?php

namespace Monitor;

use Illuminate\Database\Eloquent\Model;

class Proceeding extends Model
{
    protected $fillable = [
		'title',
		'description',
		'file_reference',
		'aplicant',
		'building_change',
		'notified_at',
		'decided_at',
		'posted_at',
		'droped_at',
		'project_id',
		'proceeding_type_id',
		'proceeding_phase_id'];
	
	//relations 
	
	public function project()
    {
        return $this->belongsTo('Monitor\Project');
    }
}
