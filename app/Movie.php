<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

use App\Actor;
use App\Director;
use App\Genre;
use App\AudioLanguage;

class Movie extends Model
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
    	'tmdb_id',
    	'duration',
    	'thumbnail',
    	'poster',
    	'tmdb',
    	'director_id',
    	'actor_id',
    	'supporting_actor',
    	'genre_id',
    	'trailer_url',
    	'detail',
    	'rating',
    	'maturity_rating',
    	'subtitle',
    	'subtitle_list',
    	'subtitle_files',
    	'publish_year',
    	'released',
    	'featured',
    	'series',
    	'a_language',
    	'audio_files',
    	'type'
    ];

    public function genre() {
    	return $this->belongsTo('App\Genre');
    }

    public function movie_series() {
    	return $this->hasMany('App\MovieSeries', 'movie_id');
    }

    public function wishlist()
    {
    	return $this->hasMany('App\Wishlist');
    }

    public function video_link()
    {
    	return $this->hasOne('App\Videolink');
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

    public function directorString() {
        $links = [];

        if ( $this->director_id ) {
            $directorIds = explode(',', $this->director_id);

            foreach ($directorIds as $director_id ) {
                $director = Director::find($director_id);
                if ( $director ) {
                    $links[$director_id] = '<a href="' . url('video/detail/director_search', trim($director->name)) . '">' . trim($director->name) . '</a>';
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
