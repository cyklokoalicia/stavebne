<?php

namespace Monitor;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
		'original_filename',
		'mime_type',
		'file_extension',
		'caption',
		'url',
		'file_size',
		'city_district_id',
		'file_process_id',
		'proceeding_id'];
}
