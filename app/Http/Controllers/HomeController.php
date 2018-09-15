<?php

namespace App\Http\Controllers;

use Auth;

use App\Actor;
use App\AudioLanguage;
use App\Director;
use App\Genre;
use App\HomeSlider;
use App\LandingPage;
use App\Menu;
use App\Movie;
use App\Package;
use App\Season;
use App\TvSeries;
use App\UserPlayingTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function mainPage()
    {
        $user = Auth::user();

        if ( $user ) {
            return redirect()->to('/home');
        } else {
            return redirect('login');
        }

    	$plans = Package::all();
    	$blocks = LandingPage::orderBy('position', 'asc')->get();
    	return view('main', compact('plans', 'blocks'));   
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($menu_slug)
    {
        $user = Auth::user();

    	$home_slides = HomeSlider::orderBy('position', 'asc')->get();

    	$menu = Menu::whereSlug($menu_slug)->first();

    	if (!isset($menu)) {
    		return redirect('/');
    	}

        $all_mix = [];

        $movies = [];
    	$fil_movies = $menu->menu_data;
    	if (count($fil_movies) > 0) {
    	    foreach ($fil_movies as $key => $value) {
                if ( $value->movie ) {
    				$movies[] = $value->movie;
    				$all_mix[] = $value->movie;
                }
    		}
    	}

    	$tvserieses = [];
    	$fil_tvserieses = $menu->menu_data;
    	if (count($fil_tvserieses) > 0) {
    		foreach ($fil_tvserieses as $key => $value) {
                if ( $value->tvseries ) {
    				$tvserieses[] = $value->tvseries;
    				
					if ($value->type == 'T') {
    					foreach ($value->seasons as $value2) {
    						$all_mix[] = $value2;
    					}
    				}
                }
    		}
    	}

    	$genres = Genre::all();
    	$a_languages = AudioLanguage::all();

        // Featured Movies Array
    	$featured_movies = [];
    	if (count($movies) > 0) {
    		foreach ($movies as $movie) {
    			if ($movie->featured == 1) {
    				$featured_movies[] = $movie;
    			}
    		}
    	}

        // Featured Tvserieses
    	$featured_seasons = [];
    	if (count($tvserieses) > 0) {
    		foreach ($tvserieses as $series) {
    			if ( $series->featured == 1 ) {
    				if ( count($series->seasons) ) {
    					foreach ( $series->seasons as $season ) {
    						$featured_seasons[] = $season;
    					}
    				}
    			}
    		}

    	}

    	return view('home', compact('home_slides', 'movies', 'tvserieses', 'a_languages', 'all_mix', 'genres', 'featured_movies', 'featured_seasons', 'menu'));
    }

    public function search(Request $searchKey)
    {

    	$all_movies = Movie::all();
    	$all_tvseries = TvSeries::all();
    	$searchKey = $searchKey->search;

    	$tvseries = TvSeries::where('title','LIKE',"%$searchKey%")->get();
    	$filter_video = collect();

    	$tvseries = TvSeries::where('title','LIKE',"%$searchKey%")->get();
    	foreach ($tvseries as $series)
    	{
    		$season = Season::where('tv_series_id', $series->id)->get();
    		if (isset($season)) {
    			$filter_video->push($season);
    		}
    	}

    	$movies = Movie::where('title','LIKE',"%$searchKey%")->get();
    	$filter_video->push($movies);

        // if search key is actor
    	$actor = Actor::where('name','LIKE',"%$searchKey%")->first();
    	if ( $actor ) {
    		foreach ($all_movies as $key => $item) {
    			if ($item->actor_id != null && $item->actor_id != '') {
    				$movie_actor_list = explode(',', $item->actor_id);
    				for($i = 0; $i < count($movie_actor_list); $i++) {
    					$check = Actor::find(intval($movie_actor_list[$i]));
    					if ( $check && $check->name == $actor->name ) {
    						$filter_video->push($item);
    					}
    				}
    			}
    		}
    		foreach ($all_tvseries as $key => $tv) {
    			foreach ($tv->seasons as $key => $item) {
    				if ($item->actor_id != null && $item->actor_id != '') {
    					$season_actor_list = explode(',', $item->actor_id);
    					for($i = 0; $i < count($season_actor_list); $i++) {
    						$check = Actor::find(intval($season_actor_list[$i]));
    						if ( $check && $check->name == $actor->name ) {
    							$filter_video->push($item);
    						}
    					}
    				}
    			}
    		}
    	}

        // if search key is director
    	$director = Director::where('name','LIKE',"%$searchKey%")->first();
    	if (isset($director) && $director != null) {
    		foreach ($all_movies as $key => $item) {
    			if ($item->director_id != null && $item->director_id != '') {
    				$movie_director_list = explode(',', $item->director_id);
    				for($i = 0; $i < count($movie_director_list); $i++) {
    					$check = Director::find(intval($movie_director_list[$i]));
    					if ( $check && $check->name == $director->name ) {
    						$filter_video->push($item);
    					}
    				}
    			}
    		}
    	}

        // if search key is genre
    	$all_genres = Genre::all();
    	if (isset($all_genres) && count($all_genres) > 0) {
    		foreach ($all_genres as $key => $value) {
    			if (trim($value->name) == trim($searchKey)) {
    				$genre = $value;
    			}
    		}
    	}

    	if (isset($genre) && $genre != null) {
    		foreach ($all_movies as $key => $item) {
    			if ($item->genre_id != null && $item->genre_id != '') {
    				$movie_genre_list = explode(',', $item->genre_id);
    				for($i = 0; $i < count($movie_genre_list); $i++) {
    					$check = Genre::find(intval($movie_genre_list[$i]));
    					if ( $check && $check->name == $genre->name) {
    						$filter_video->push($item);
    					}
    				}
    			}
    		}

    		foreach ($all_tvseries as $key => $item) {
    			if ($item->genre_id != null && $item->genre_id != '') {
    				$tv_genre_list = explode(',', $item->genre_id);
    				for($i = 0; $i < count($tv_genre_list); $i++) {
    					$check = Genre::find(intval($tv_genre_list[$i]));
    					if ( $check && $check->name == $actor->name) {
    						$filter_video->push($item);
    					}
    				}
    			}
    		}
    	}


    	$filter_video = $filter_video->flatten();  

    	return view('search', compact('filter_video', 'searchKey'));
    }

    public function director_search($director_search)
    {
        $director = Director::where('name','LIKE',"%$director_search%")->first();
        if ( !$director ) {
            abort(404);
        }

    	$filter_video = collect();
    	$all_movies = Movie::all();
    	$tvseries = TvSeries::all();
    	$searchKey = $director_search;

    	if ($searchKey != null || $searchKey != '') {
    		foreach ($all_movies as $item) {
    			if ($item->director_id != null && $item->director_id != '') {
    				$movie_director_list = explode(',', $item->director_id);
    				for($i = 0; $i < count($movie_director_list); $i++) {
    					$check = Director::find(intval($movie_director_list[$i]));
    					if ( $check && $check->name == $director->name ) {
    						$filter_video->push($item);
    					}
    				}
    			}
    		}
    	}

    	$filter_video =  $filter_video->filter(function($value, $key) {
    		return  $value != null;
    	});

    	$filter_video = $filter_video->flatten();
    	return view('search', compact('filter_video', 'searchKey'));
    }

    public function actor_search($actor_search)
    {
        $actor = Actor::where('name','LIKE',"%$actor_search%")->first();
        if ( !$actor ) {
            abort(404);
        }

    	$filter_video = collect();
    	$all_movies = Movie::all();
    	$tvseries = TvSeries::all();
    	$searchKey = $actor_search;

    	if ($searchKey != null || $searchKey != '') {
    		foreach ($all_movies as $item) {
    			if ($item->actor_id != null && $item->actor_id != '') {
    				$movie_actor_list = explode(',', $item->actor_id);
    				for($i = 0; $i < count($movie_actor_list); $i++) {
    					$check = Actor::find(intval($movie_actor_list[$i]));
    					if ( $check && $check->name == $actor->name ) {
    						$filter_video->push($item);
    					}
    				}
    			}
    		}
    		if (isset($tvseries) && count($tvseries) > 0) {
    			foreach ($tvseries as $series) {
    				if (isset($series->seasons) && count($series->seasons) > 0) {
    					foreach ($series->seasons as $item) {
    						if ($item->actor_id != null && $item->actor_id != '') {
    							$season_actor_list = explode(',', $item->actor_id);
    							for($i = 0; $i < count($season_actor_list); $i++) {
    								$check = Actor::find(intval($season_actor_list[$i]));
    								if ( $check && $check->name == $actor->name ) {
    									$filter_video->push($item);
    								}
    							}
    						}
    					}
    				}
    			}
    		}
    	}

    	$filter_video =  $filter_video->filter(function($value, $key) {
    		return  $value != null;
    	});

    	$filter_video = $filter_video->flatten();
    	return view('search', compact('filter_video', 'searchKey'));
    }

    public function genre_search($genre_search)
    {
    	$all_genres = Genre::all();
    	$all_movies = Movie::all();
    	$all_tvseries = TvSeries::all();
    	$filter_video = collect();

    	if (isset($all_genres) && count($all_genres) > 0) {
    		foreach ($all_genres as $key => $value) {
    			if (trim($value->name) == trim($genre_search)) {
    				$genre = $value;
    			}
    		}
    	}

    	$searchKey = $genre_search;
    	if ($genre != null) {
    		foreach ($all_movies as $item) {
    			if ($item->genre_id != null && $item->genre_id != '') {
    				$movie_genre_list = explode(',', $item->genre_id);
    				for($i = 0; $i < count($movie_genre_list); $i++) {
    					$check = Genre::find(intval($movie_genre_list[$i]));
    					if ( $check && $check->name == $genre->name ) {
    						$filter_video->push($item);
    					}
    				}
    			}
    		}

    		if (isset($all_tvseries) && count($all_tvseries) > 0) {
    			foreach ($all_tvseries as $series) {
    				if (isset($series->seasons) && count($series->seasons) > 0) {
    					if ($series->genre_id != null && $series->genre_id != '') {
    						$tvseries_genre_list = explode(',', $series->genre_id);
    						for($i = 0; $i < count($tvseries_genre_list); $i++) {
    							$check = Genre::find(intval($tvseries_genre_list[$i]));
    							if ( $check && $check->name == $genre->name ) {
    								$filter_video->push($series->seasons);
    							}
    						}
    					}
    				}
    			}
    		}
    	}

    	$filter_video =  $filter_video->filter(function($value, $key) {
    		return  $value != null;
    	});

    	$filter_video = $filter_video->flatten();

    	return view('search', compact('filter_video', 'searchKey'));
    }

    public function movie_genre($id)
    {
        $genre = Genre::find($id);
        if ( !$genre ) {
            abort(404);
        }

    	$all_movies = Movie::all();
    	
    	$movies = [];
    	$searchKey = $genre->name;
    	foreach ($all_movies as $item) {
    		if ($item->imdb != 'Y') {
    			if ($item->genre_id != null && $item->genre_id != '') {
    				$movie_genre_list = explode(',', $item->genre_id);
    				for($i = 0; $i < count($movie_genre_list); $i++) {
    					$check = Genre::find(intval($movie_genre_list[$i]));
    					if ( $check && $check->id == $genre->id ) {
    						$movies[] = $item;
    					}
    				}
    			}
    		} else {
    			if ($item->genre_id != null && $item->genre_id != '') {
    				$movie_genre_list = explode(',', $item->genre_id);
    				for($i = 0; $i < count($movie_genre_list); $i++) {
    					$check = Genre::find(intval($movie_genre_list[$i]));
    					if ( $check && $check->name == $genre->name ) {
    						$movies[] = $item;
    					}
    				}
    			}
    		}   
    	}

    	$filter_video = $movies;

    	return view('search', compact('filter_video', 'searchKey'));
    }


    public function tvseries_genre($id)
    {
        $genre = Genre::find($id);
        if ( !$genre ) {
            abort(404);
        }

    	$all_tvseries = TvSeries::all();
    	$searchKey = $genre->name;
    	$seasons = collect();

    	foreach ($all_tvseries as $item) {
    		if ($item->imdb != 'Y') {
    			if ($item->genre_id != null && $item->genre_id != '') {
    				$tvseries_genre_list = explode(',', $item->genre_id);
    				for($i = 0; $i < count($tvseries_genre_list); $i++) {
    					$check = Genre::find(intval($tvseries_genre_list[$i]));
    					if ( $check && $check->id == $genre->id ) {
    						$seasons->push($item->seasons);
    					}
    				}
    			}
    		} else {
    			if ($item->genre_id != null && $item->genre_id != '') {
    				$tvseries_genre_list = explode(',', $item->genre_id);
    				for($i = 0; $i < count($tvseries_genre_list); $i++) {
    					$check = Genre::find(intval($tvseries_genre_list[$i]));
    					if ( $check && $check->name == $genre->name ) {
    						$seasons->push($item->seasons);
    					}
    				}
    			}
    		}   
    	}

    	$filter_video = $seasons->shuffle()->flatten();
    	return view('search', compact('filter_video', 'searchKey'));
    }

    public function movie_language($language_id)
    {
    	$lang = AudioLanguage::find($language_id);

        if ( !$lang ) {
            abort(404);
        }

    	$searchKey = $lang->language;
    	$all_movies = Movie::all();
    	
    	$filter_video = [];
    	foreach ($all_movies as $item) {
    		if ($item->a_language != null && $item->a_language != '') {
    			$movie_lang_list = explode(',', $item->a_language);
    			for($i = 0; $i < count($movie_lang_list); $i++) {
    				$check = Genre::find(intval($movie_lang_list[$i]));
    				if ( $check && $check->id == $lang->id ) {
    					$filter_video[] = $item;
    				}
    			}
    		}
    	}

    	return view('search', compact('filter_video', 'searchKey'));
    }

    public function tvseries_language($language_id) {
        $lang = AudioLanguage::find($language_id);
        if ( !$lang ) {
            abort(404);
        }

    	$searchKey = $lang->language;
    	$all_seasons = Season::all();
    	$filter_video = [];

    	foreach ($all_seasons as $item) {
    		if ($item->a_language != null && $item->a_language != '') {
    			$season_lang_list = explode(',', $item->a_language);
    			for($i = 0; $i < count($season_lang_list); $i++) {
    				$check = Genre::find(intval($season_lang_list[$i]));
    				if ( $check && $check->id == $lang->id ) {
    					$filter_video[] = $item;
    				}
    			}
    		}
    	}

    	return view('search', compact('filter_video', 'searchKey'));
    }

    // Store the current playing time by ajax
    public function storeTime(Request $request) {
    	$user = Auth::user();

        if ( !$user ) {
            return response()->json(['result' => 'failure']);
        }

    	$id = intval($request->input('id'));
    	$index = intval($request->input('index'));
    	$type = $request->input('type');
    	$time = intval($request->input('time'));
    	$duration = intval($request->input('duration'));
    	$end = intval($request->input('end'));

    	// Need to remove after completed watching
    	if ( $end ) {
    		if ( $type == 'M' ) {
	    		UserPlayingTime::where('user_id', $user->id)
	    						->where('type', $type)
	    						->where('item_id', $id)
	    						->delete();
			} else if ( $type == 'S' ) {
	    		UserPlayingTime::where('user_id', $user->id)
	    						->where('type', $type)
	    						->where('item_id', $id)
	    						->where('index', $index)
	    						->delete();
	    	}
    	} else {

    		// Check if already added
    		if ( $type == 'M' ) {
	    		$storing = UserPlayingTime::where('user_id', $user->id)
				    						->where('type', $type)
				    						->where('item_id', $id)
				    						->orderBy('id', 'desc')
				    						->first();
			} else if ( $type == 'S') {
				$storing = UserPlayingTime::where('user_id', $user->id)
				    						->where('type', $type)
				    						->where('item_id', $id)
				    						->where('index', $index)
				    						->orderBy('id', 'desc')
				    						->first();
			}
    		
    		if ( !$storing ) {
    			$storing = new UserPlayingTime;
    			$storing->user_id = $user->id;
    			$storing->item_id = $id;
    			$storing->type = $type;
    		}
    		
    		if ( $type == 'S' ) {
    			$storing->index = $index;
    		}

    		$storing->time = $time;
    		$storing->duration = $duration;
    		$storing->save();
    	}

    	return response()->json(['result' => 'success']);
    }

    // Store the current audio track id by ajax
    public function storeAudioTrack(Request $request) {
        $user = Auth::user();

        if ( !$user ) {
            return response()->json(['result' => 'failure']);
        }

        $track_no = intval($request->input('track_no'));

        if ( $track_no < 0 ) {
            $track_no = 0;
        }

        // Store the current audio track no
        $user->audio_track_no = $track_no;
        $user->save();

        return response()->json(['result' => 'success']);
    }

}
