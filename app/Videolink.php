<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Videolink extends Model
{
    protected $fillable = [
    	'movie_id',
    	'episode_id',
    	'ready_url',
    	'url_360',
    	'url_480',
    	'url_720',
    	'url_1080'
    ];
}
