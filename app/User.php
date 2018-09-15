<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Cashier\Billable;

use App\Movie;
use App\Season;
use App\TvSeries;
use App\Wishlist;
use App\UserPlayingTime;

class User extends Authenticatable
{
	use Notifiable, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name', 'image', 'email', 'password', 'is_admin', 'stripe_id', 'card_brand', 'card_last_four', 'trial_ends_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    	'password', 'remember_token',
    ];

    public function wishlist()
    {
    	return $this->hasMany('App\Wishlist', 'user_id');
    }

    public function paypal_subscriptions()
    {
    	return $this->hasMany('App\PaypalSubscription');
    }

    public function subscriptions()
    {
    	return $this->hasMany('Laravel\Cashier\Subscription');
    }

    public function addedWishlist($id, $type) {
    	return Wishlist::where('user_id', $this->id)
    					->where('item_id', $id)
						->where('item_type', $type)
						->first();
    }

    public function continueWatchings() {
    	$result = [];

    	$watchings = UserPlayingTime::where('user_id', $this->id)
    								->groupBy('item_id', 'type')
							    	->orderBy('id', 'DESC')
							    	->get();

    	if ( count($watchings) ) {
    		foreach ( $watchings as $watch ) {
    			if ( isset($ids[$watch->type]) && in_array($watch->item_id, $ids[$watch->type]) ) {
    				continue;
    			}

				if ( $watch->type == 'M' ) {
					if ( $item = Movie::find($watch->item_id) ) {
						$result[] = $item;
					}
				} else if ( $watch->type == 'S' ) {
					if ( $item = Season::find($watch->item_id) ) {
						$result[] = $item;
					}
				} else if ( $watch->type == 'S' ) {
					if ( $item = TvSeries::find($watch->item_id) ) {
						$result[] = $item;
					}
				}
    		}
    	}

    	return $result;
    }
}
