<?php

namespace App\Http\Controllers;

use Auth;

use App\Config;
use App\Movie;
use App\MovieSeries;
use App\Season;
use App\TvSeries;

use Illuminate\Http\Request;

class PrimeDetailController extends Controller
{
	/**
	* @param $id
	* @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
	*/
	public function showMovie($id) {
	  	$movie =  Movie::find($id);

	  	if ( !$movie ) {
			abort(404);
		}

	  	$movies = Movie::all();
	  	$config = Config::findOrFail(1);
	  	$filter_series = [];

	  	if ( $movie->series ) {
	  		$single_series_list = MovieSeries::where('series_movie_id', $id)->first();
	  		if ( $single_series_list ) {
	  			$main_movie_series = Movie::find($single_series_list->movie_id);

	  			if ( $main_movie_series ) {
		  			$filter_series[$main_movie_series->id] = $main_movie_series;

		  			$series_list = MovieSeries::where([
		  				['movie_id', $main_movie_series->id], 
		  				['series_movie_id', '!=', $id]
		  			])->get();

		  			if ( count($series_list) ) {
			  			foreach ($series_list as $item) {
			  				$filter_movie_exc_self = Movie::find($item->series_movie_id);

			  				if ( $filter_movie_exc_self ) {
			  					$filter_series[$filter_movie_exc_self->id] = $filter_movie_exc_self;
			  				}
			  			}
			  		}
		  		}
	  		}
	  	}

	  	$view = 'movie_single';

	  	if ($config->prime_movie_single == 1) {
	  		$view = 'movie_single_prime';
	  	}

	  	return view($view, [
	  		'movie' => $movie, 
	  		'genreString' => $movie->genreString(),
	  		'actors' => $movie->actorString(),
	  		'directors' => $movie->directorString(),
	  		'sub_titles' => $movie->subTitleString(),
	  		'audio_languages' => $movie->audioLangString(),
	  		'movies' => $movies, 
	  		'filter_series' => $filter_series,
	  	]);
 	}

	public function showSeasons($id) {
		$season = Season::findOrFail($id);
		$config = Config::findOrFail(1);

		if ( !$season ) {
			abort(404);
		}

		$view = 'seasons_single';
		if ($config->prime_movie_single == 1) {
	  		$view = 'seasons_single_prime';
	  	}

	  	return view($view, [
	  		'season' => $season,
	  		'genreString' => $season->tvseries->genreString(),
	  		'actors' => $season->actorString(),
	  		'sub_titles' => $season->subTitleString(),
	  		'audio_languages' => $season->audioLangString(),
	  	]);
	}

}
