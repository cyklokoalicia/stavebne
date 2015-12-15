<?php

namespace Monitor;

use Illuminate\Database\Eloquent\Model;

class Proceeding extends Model
{
    protected $fillable = [
		'file_reference',
		'aplicant',
		'notified_at',
		'decided_at',
		'posted_at',
		'droped_at',
		'project_id',
		'proceeding_id',
		'proceeding_type_id'];
}
