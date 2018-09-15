<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
	protected $fillable = [
		'user_id',
		'item_id',
		'item_type',
	];

	public function users()
	{
		return $this->belongsTo('App\User');
	}

	public function item() {
		if ( $this->item_type == 'M' ) {
			return $this->hasOne('App\Movie', 'id', 'item_id');
		} else if ( $this->item_type == 'S' ) {
			return $this->hasOne('App\Season', 'id', 'item_id');
		} else if ( $this->item_type == 'S' ) {
			return $this->hasOne('App\TvSeries', 'id', 'item_id');			
		}

		return false;
	}
}
