<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
		'title',
		'file_reference',
		'caption',
		'filesize',
		'project_id'];
}
