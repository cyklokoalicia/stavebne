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
		'file_size',
		'metadata',
		'ocr_processed',
		'proceeding_id'];
}
