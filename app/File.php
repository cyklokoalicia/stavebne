<?php

namespace Monitor;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $fillable = [
		'filename',
		'mime_type',
		'caption',
		'filesize'];
}
