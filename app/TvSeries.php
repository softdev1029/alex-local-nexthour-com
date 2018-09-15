<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

use App\Actor;
use App\Genre;
use App\AudioLanguage;
use App\Wishlist;

class TvSeries extends Model
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
    	'title',
    	'tmdb',
    	'tmdb_id',
    	'thumbnail',
    	'poster',
    	'genre_id',
    	'detail',
    	'rating',
    	'maturity_rating',
    	'featured',
    	'type'
    ];

    public function seasons() {
    	return $this->hasMany('App\Season', 'tv_series_id');
    }

    public function wishlist()
    {
    	return $this->hasMany('App\Wishlist');
    }

    public function homeslide()
    {
    	return $this->hasMany('App\HomeSlider', 'tv_series_id');
    }

    public function menus()
    {
    	return $this->hasMany('App\MenuVideo');
    }

    public function genreString() {
    	$links = [];

    	if ( $this->genre_id ) {
			$genreIds = explode(',', $this->genre_id);

			foreach ($genreIds as $genre_id ) {
				$genre = Genre::find($genre_id);
				if ( $genre ) {
					$links[] = '<a href="' . url('video/detail/genre_search', trim($genre->name)) . '">' . trim($genre->name) . '</a>';
				}
			}
    	}

		if ( $links ) {
			return implode(', ', $links);
		}

    	return '';
    }

    public function seasonMeta() {
    	$meta = [];

    	if ( $this->seasons->count() ) {
    		foreach ( $this->seasons as $season ) {
    			// Actors
		    	if ( $season->actor_id ) {
					$actorIds = explode(',', $season->actor_id);

					foreach ($actorIds as $actor_id ) {
						$actor = Actor::find($actor_id);
						if ( $actor ) {
							$meta['actors'][$actor_id] = '<a href="' . url('video/detail/actor_search', trim($actor->name)) . '">' . trim($actor->name) . '</a>';
						}
					}
		    	}

		    	// Subtitle languages
		    	if ( $season->subtitle && $season->subtitle_list ) {
					$langIds = explode(',', $season->subtitle_list);

					foreach ($langIds as $lang_id ) {
						$lang = AudioLanguage::find($lang_id);
						if ( $lang ) {
							$meta['audio_languages'][$lang_id] = $lang->language;
						}
					}
		    	}

				// Audio languages
		    	if ( $season->a_language ) {
					$langIds = explode(',', $season->a_language);

					foreach ($langIds as $lang_id ) {
						$lang = AudioLanguage::find($lang_id);
						if ( $lang ) {
							$meta['sub_titles'][$lang_id] = $lang->language;
						}
					}
		    	}
    		}
    	}

    	return $meta;
    }

    public function actorString() {
    	$links = [];

    	if ( $this->seasons->count() ) {
    		foreach ( $this->seasons as $season ) {
		    	if ( $season->actor_id ) {
					$actorIds = explode(',', $season->actor_id);

					foreach ($actorIds as $actor_id ) {
						$actor = Actor::find($actor_id);
						if ( $actor ) {
							$links[$actor_id] = '<a href="' . url('video/detail/actor_search', trim($actor->name)) . '">' . trim($actor->name) . '</a>';
						}
					}
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

    	if ( $this->seasons->count() ) {
    		foreach ( $this->seasons as $season ) {
		    	if ( $season->subtitle && $season->subtitle_list ) {
					$langIds = explode(',', $season->subtitle_list);

					foreach ($langIds as $lang_id ) {
						$lang = AudioLanguage::find($lang_id);
						if ( $lang ) {
							$langs[$lang_id] = $lang->language;
						}
					}
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

    	if ( $this->seasons->count() ) {
    		foreach ( $this->seasons as $season ) {
		    	if ( $season->a_language ) {
					$langIds = explode(',', $season->a_language);

					foreach ($langIds as $lang_id ) {
						$lang = AudioLanguage::find($lang_id);
						if ( $lang ) {
							$langs[$lang_id] = $lang->language;
						}
					}
		    	}
    		}
    	}

    	if ( $langs ) {
			return implode(', ', $langs);
		}

    	return '';
    }
}
