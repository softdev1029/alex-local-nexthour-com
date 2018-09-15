<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPlayingTime extends Model {

	/**
   	* The table associated with the model.
   	*
   	* @var string
   	*/
	protected $table = 'user_playing_times';

    /**
    * Indicates if the model should be timestamped.
    *
    * @var bool
    */
    public $timestamps = true;

    function __construct() {
        parent::__construct();
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function movie() {
    	return $this->hasOne('App\Movie', 'id', 'item_id');
  	}

  	public function season() {
    	return $this->hasOne('App\Season', 'id', 'item_id');
  	}
}