<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

use App\Actor;
use App\Genre;
use App\AudioLanguage;

class Season extends Model
{
	use HasTranslations;

	public $translatable = ['detail'];

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
    	$attributes = parent::toArray();
    	
    	foreach ($this->getTranslatableAttributes() as $name) {
    		$attributes[$name] = $this->getTranslation($name, app()->getLocale());
    	}
    	
    	return $attributes;
    }

    protected $fillable = [
    	'tv_series_id',
    	'season_no',
    	'publish_year',
    	'a_language',
    	'subtitle',
    	'subtitle_list',
    	'type',
    	'thumbnail',
    	'poster',
    	'tmdb_id',
    	'tmdb',
    	'detail',
    	'actor_id'
    ];

    public function episodes() {
    	return $this->hasMany('App\Episode', 'seasons_id');
    }

    public function tvseries() {
    	return $this->belongsTo('App\TvSeries', 'tv_series_id');
    }

    public function wishlist()
    {
    	return $this->hasMany('App\Wishlist');
    }

    public function actorString() {
        $links = [];

	    if ( $this->actor_id ) {
	        $actorIds = explode(',', $this->actor_id);

	        foreach ($actorIds as $actor_id ) {
	            $actor = Actor::find($actor_id);
	            if ( $actor ) {
	                $links[$actor_id] = '<a href="' . url('video/detail/actor_search', trim($actor->name)) . '">' . trim($actor->name) . '</a>';
	            }
	        }
	    }

        if ( $links ) {
            return implode(', ', $links);
        }

        return '';
    }

    public function subTitleString() {
        $langs = [];

        if ( $this->subtitle && $this->subtitle_list ) {
            $langIds = explode(',', $this->subtitle_list);

            foreach ($langIds as $lang_id ) {
                $lang = AudioLanguage::find($lang_id);
                if ( $lang ) {
                    $langs[$lang_id] = $lang->language;
                }
            }
        }

        if ( $langs ) {
            return implode(', ', $langs);
        }

        return '';
    }

    public function audioLangString() {
        $langs = [];

        if ( $this->a_language ) {
            $langIds = explode(',', $this->a_language);

            foreach ($langIds as $lang_id ) {
                $lang = AudioLanguage::find($lang_id);
                if ( $lang ) {
                    $langs[$lang_id] = $lang->language;
                }
            }
        }

        if ( $langs ) {
            return implode(', ', $langs);
        }

        return '';
    }
}
