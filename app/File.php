<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $fillable = [
		'filename',
		'mime_type',
		'caption',
		'filesize'];
}
