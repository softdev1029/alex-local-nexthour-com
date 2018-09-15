<?php

namespace App\Http\Controllers;

use App\Actor;
use App\Menu;
use App\MenuVideo;
use App\AudioLanguage;
use App\Episode;
use App\Genre;
use App\Season;
use App\TvSeries;
use App\Videolink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TvSeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$tv_serieses = TvSeries::all();
    	return view('admin.tvseries.index', compact('tv_serieses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
    	$menus = Menu::all();
    	$genre_ls = Genre::pluck('name', 'id')->all();
    	$a_lans = AudioLanguage::pluck('language', 'id')->all();

    	return view('admin.tvseries.create', compact('actor_ls', 'genre_ls', 'a_lans', 'menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	ini_set('max_execution_time', 80);

    	$request->validate([
    		'title' => 'required'
    	]);

    	$menus = null;

    	if (isset($request->menu) && count($request->menu) > 0) {
    		$menus = $request->menu;
    	}

    	$TMDB_API_KEY = env('TMDB_API_KEY');
    	$input = $request->all();

    	if ($input['tmdb'] != 'Y') {
    		$request->validate([
    			'genre_id' => 'required'
    		]);
    	}

    	if(!isset($input['featured']))
    	{
    		$input['featured'] = 0;
    	}

    	if ($input['tmdb'] == 'Y') {

    		if ($TMDB_API_KEY == null || $TMDB_API_KEY == '') {
    			return back()->with('deleted', 'Please provide your TMDB api key or add tvseries by custom fields');
    		}

    		$title = urlencode($input['title']);
    		$search_data = @file_get_contents('https://api.themoviedb.org/3/search/tv?api_key='.$TMDB_API_KEY.'&query='.$title);

    		if ($search_data) {
    			$data = json_decode($search_data, true);
    		}

    		if (!isset($data) || $data['results'] == null) {
    			return back()->with('deleted', 'Tv Series does not found by tmdb servers !');
    		}

    		if (Session::has('changed_language')) {
    			$fetch_tv = @file_get_contents('https://api.themoviedb.org/3/tv/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
    			$fetch_tv_for_genres = @file_get_contents('https://api.themoviedb.org/3/tv/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
    		} else {
    			$fetch_tv = @file_get_contents('https://api.themoviedb.org/3/tv/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
    			$fetch_tv_for_genres = @file_get_contents('https://api.themoviedb.org/3/tv/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
    		}

    		if (!isset($fetch_tv) && !isset($fetch_tv_for_genres)) {
    			return back()->with('deleted', 'Tv Series does not found by tmdb servers !');
    		}

    		$tmdb_tv = json_decode($fetch_tv, true);
          // Only for genre
    		$tmdb_tv_for_genres = json_decode($fetch_tv_for_genres, true);

    		if ($tmdb_tv != null) {
    			$input['tmdb_id'] = $tmdb_tv['id'];
    		} else {
    			return back()->with('deleted', 'Tv Series does not found by tmdb servers !');
    		}

    		$thumbnail = null;
    		$poster = null;

    		if ($file = $request->file('thumbnail')) {

    			$thumbnail = 'thumb_'.time().$file->getClientOriginalName();
    			$file->move('images/tvseries/thumbnails', $thumbnail);

    		} else {

    			$url = $tmdb_tv['poster_path'];
    			$contents = @file_get_contents('https://image.tmdb.org/t/p/w500/'.$url);
    			$name = substr($url, strrpos($url, '/') + 1);
    			$name = 'tmdb_'.$name;
    			if ($contents) {  
    				$tmdb_img = Storage::disk('imdb_poster_tv_series')->put($name, $contents);
    				if ($tmdb_img) {
    					$thumbnail = $name;              
    				}
    			}

    		}

    		if ($file = $request->file('poster')) {
    			$poster = 'poster_'.time().$file->getClientOriginalName();
    			$file->move('images/tvseries/posters', $poster);
    		} else {
    			$url = $tmdb_tv['backdrop_path'];
    			$contents = @file_get_contents('https://image.tmdb.org/t/p/original/'.$url);
    			$name = substr($url, strrpos($url, '/') + 1);
    			$name = 'tmdb_'.$name;
    			if ($contents) {  
    				$tmdb_img = Storage::disk('imdb_backdrop_tv_series')->put($name, $contents);
    				if ($tmdb_img) {
    					$poster = $name;              
    				}
    			}
    		}

          // get Genres and create theme
    		$tmdb_genres_id = collect();
    		if (isset($tmdb_tv_for_genres) && $tmdb_tv_for_genres != null) {
    			foreach ($tmdb_tv_for_genres['genres'] as $tmdb_genre) {

    				$check_list = Genre::where('name',$tmdb_genre['name'])->first();

    				if (!isset($check_list)) {
    					$created_genre = Genre::create([
    						'name' => [
    							'en' => $tmdb_genre['name']
    						],
    					]);

    					$tmdb_genres_id->push($created_genre->id);
    				} else {
    					$tmdb_genres_id->push($check_list->id);
    				}
    			}
    		}
    		$tmdb_genres_id = $tmdb_genres_id->flatten();
    		$tmdb_genres_id = substr($tmdb_genres_id, 1, -1);

    		$created_tv = TvSeries::create([
    			'title' => $input['title'],
    			'tmdb_id' => $input['tmdb_id'],
    			'tmdb' => $input['tmdb'],
    			'featured' => $input['featured'],
    			'thumbnail' => $thumbnail,
    			'poster' => $poster,
    			'genre_id' => $tmdb_genres_id,
    			'detail' => $tmdb_tv['overview'],
    			'rating' => $tmdb_tv['vote_average'],
    			'episode_runtime' => $tmdb_tv['episode_run_time'],
    			'maturity_rating' => $input['maturity_rating']
    		]);

    		if ($menus != null) {
    			if (count($menus) > 0) {
    				foreach ($menus as $key => $value) {
    					MenuVideo::create([
    						'menu_id' => $value,
    						'tv_series_id' => $created_tv->id,
    					]);
    				}
    			}
    		}

    		return back()->with('added', 'Tv Series has been added');
    	}

    	$genre_ids = $request->input('genre_id');
    	if ($genre_ids) {
    		$genre_ids = implode(',', $genre_ids);
    		$input['genre_id'] = $genre_ids;
    	} else {
    		$input['genre_id'] = null;
    	}

    	if ($file = $request->file('thumbnail')) {
    		$thumbnail = 'thumb_'.time().$file->getClientOriginalName();
    		$file->move('images/tvseries/thumbnails', $thumbnail);
    		$input['thumbnail'] = $thumbnail;
    	}

    	if ($file = $request->file('poster')) {
    		$poster = 'poster_'.time().$file->getClientOriginalName();
    		$file->move('images/tvseries/posters', $poster);
    		$input['poster'] = $poster;
    	}

    	TvSeries::create($input);
    	return back()->with('added', 'Tv Series has been added');
    }

  /**
   * Show the form for editing the specified resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
  	$menus = Menu::all();
  	$tvseries = TvSeries::findOrFail($id);
  	$genre_ls = Genre::all();
  	$a_lans = AudioLanguage::all();

        // get old genre list 
  	$old_genre = collect();
  	if ($tvseries->genre_id != null){
  		$old_list = explode(',', $tvseries->genre_id);
  		for ($i = 0; $i < count($old_list); $i++) {
  			$old = Genre::find($old_list[$i]);
  			if (isset($old)) {
  				$old_genre->push($old);
  			}
  		}
  	}
  	$genre_ls = $genre_ls->diff($old_genre);

  	return view('admin.tvseries.edit', compact('tvseries', 'actor_ls', 'genre_ls', 'a_lans', 'old_actor', 'old_genre', 'menus'));
  }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	ini_set('max_execution_time', 80);
    	$TMDB_API_KEY = env('TMDB_API_KEY');
    	$tvseries = TvSeries::findOrFail($id);

    	$menus = null;

    	if (isset($request->menu) && count($request->menu) > 0) {
    		$menus = $request->menu;
    	}

    	$input = $request->all();

    	if ($input['tmdb'] != 'Y') {
    		$request->validate([
    			'genre_id' => 'required'
    		]);
    	}

    	if(!isset($input['featured']))
    	{
    		$input['featured'] = 0;
    	}

    	if ($input['tmdb'] == 'Y') {

    		$title = urlencode($input['title']);
    		$search_data = @file_get_contents('https://api.themoviedb.org/3/search/tv?api_key='.$TMDB_API_KEY.'&query='.$title);

    		if ($search_data) {
    			$data = json_decode($search_data, true);
    		}

    		if (!isset($data) || $data['results'] == null) {
    			return back()->with('deleted', 'Tv Series does not found by tmdb servers !');
    		}

    		if (Session::has('changed_language')) {
    			$fetch_tv = @file_get_contents('https://api.themoviedb.org/3/tv/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
    			$fetch_tv_for_genres = @file_get_contents('https://api.themoviedb.org/3/tv/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
    		} else {
    			$fetch_tv = @file_get_contents('https://api.themoviedb.org/3/tv/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
    			$fetch_tv_for_genres = @file_get_contents('https://api.themoviedb.org/3/tv/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
    		}

    		if (!isset($fetch_tv) && !isset($fetch_tv_for_genres)) {
    			return back()->with('deleted', 'Tv Series does not found by tmdb servers !');
    		}

    		$tmdb_tv = json_decode($fetch_tv, true);
          // only for genres
    		$tmdb_tv_for_genres = json_decode($fetch_tv_for_genres, true);

    		if ($tmdb_tv != null) {
    			$input['tmdb_id'] = $tmdb_tv['id'];
    		} else {
    			return back()->with('deleted', 'Tv Series does not found by tmdb servers !');
    		}

    		$thumbnail = null;
    		$poster = null;

    		if ($file = $request->file('thumbnail')) {
    			$thumbnail = 'thumb_'.time().$file->getClientOriginalName();
    			$content = @file_get_contents(public_path().'/images/tvseries/thumbnails/'.$tvseries->thumbnail);
    			if ($content) { 
    				unlink(public_path()."/images/tvseries/thumbnails/".$tvseries->thumbnail);
    			}
    			$file->move('images/tvseries/thumbnails', $thumbnail);
    		} else {
    			$url = $tmdb_tv['poster_path'];
    			$contents = @file_get_contents('https://image.tmdb.org/t/p/w500/'.$url);
    			$name = substr($url, strrpos($url, '/') + 1);
    			$name = 'tmdb_'.$name;
    			if ($contents) {  
    				if ($tvseries->thumbnail != null) {
    					$content = @file_get_contents(public_path().'/images/tvseries/thumbnails/'.$tvseries->thumbnail);
    					if ($content) { 
    						unlink(public_path()."/images/tvseries/thumbnails/".$tvseries->thumbnail);
    					}
    				}
    				$tmdb_img = Storage::disk('imdb_poster_tv_series')->put($name, $contents);
    				if ($tmdb_img) {
    					$thumbnail = $name;              
    				}
    			}
    		}

    		if ($file = $request->file('poster')) {
    			$poster = 'poster_'.time().$file->getClientOriginalName();
    			$content = @file_get_contents(public_path().'/images/tvseries/posters/'.$tvseries->poster);
    			if ($content) { 
    				unlink(public_path()."/images/tvseries/posters/".$tvseries->poster);
    			}
    			$file->move('images/tvseries/posters', $poster);
    		} else {
    			$url = $tmdb_tv['backdrop_path'];
    			$contents = @file_get_contents('https://image.tmdb.org/t/p/original/'.$url);
    			$name = substr($url, strrpos($url, '/') + 1);
    			$name = 'tmdb_'.$name;
    			if ($contents) {  
    				if ($tvseries->poster != null) {
    					$content = @file_get_contents(public_path().'/images/tvseries/posters/'.$tvseries->poster);
    					if ($content) { 
    						unlink(public_path()."/images/tvseries/posters/".$tvseries->poster);
    					}
    				}
    				$tmdb_img = Storage::disk('imdb_backdrop_tv_series')->put($name, $contents);
    				if ($tmdb_img) {
    					$poster = $name;              
    				}
    			}
    		}

          // get Genres and create theme
    		$tmdb_genres_id = collect();
    		if (isset($tmdb_tv_for_genres) && $tmdb_tv_for_genres != null) {
    			foreach ($tmdb_tv_for_genres['genres'] as $tmdb_genre) {

    				$check_list = Genre::where('name',$tmdb_genre['name'])->first();

    				if (!isset($check_list)) {
    					$created_genre = Genre::create([
    						'name' => [
    							'en' => $tmdb_genre['name']
    						],
    					]);

    					$tmdb_genres_id->push($created_genre->id);
    				} else {
    					$tmdb_genres_id->push($check_list->id);
    				}
    			}
    		}

    		$tmdb_genres_id = $tmdb_genres_id->flatten();
    		$tmdb_genres_id = substr($tmdb_genres_id, 1, -1);

    		$tvseries->update([
    			'title' => $input['title'],
    			'tmdb_id' => $input['tmdb_id'],
    			'tmdb' => $input['tmdb'],
    			'featured' => $input['featured'],
    			'thumbnail' => $thumbnail,
    			'poster' => $poster,
    			'genre_id' => $tmdb_genres_id,
    			'detail' => $tmdb_tv['overview'],
    			'rating' => $tmdb_tv['vote_average'],
    			'maturity_rating' => $input['maturity_rating']
    		]);

    		if ($menus != null) {
    			if (count($menus) > 0) {
    				if (isset($tvseries->menus) && count($tvseries->menus) > 0) {
    					foreach ($tvseries->menus as $key => $value) {
    						$value->delete();
    					}
    				}
    				foreach ($menus as $key => $value) {
    					MenuVideo::create([
    						'menu_id' => $value,
    						'tv_series_id' => $tvseries->id,
    					]);
    				}
    			}
    		} else {
    			if (isset($tvseries->menus) && count($tvseries->menus) > 0) {
    				foreach ($tvseries->menus as $key => $value) {
    					$value->delete();
    				}
    			}
    		}

    		return redirect('admin/tvseries')->with('updated', 'Tv Series has been updated');
    	}

    	$genre_ids = $request->input('genre_id');
    	if ($genre_ids) {
    		$genre_ids = implode(',', $genre_ids);
    		$input['genre_id'] = $genre_ids;
    	} else {
    		$input['genre_id'] = null;
    	}

    	if ($file = $request->file('thumbnail')) {
    		$thumbnail = 'thumb_'.time().$file->getClientOriginalName();
    		$content = @file_get_contents(public_path().'/images/tvseries/thumbnails/'.$tvseries->thumbnail);
    		if ($content) { 
    			unlink(public_path()."/images/tvseries/thumbnails/".$tvseries->thumbnail);
    		}
    		$file->move('images/tvseries/thumbnails', $thumbnail);
    		$input['thumbnail'] = $thumbnail;
    	}

    	if ($file = $request->file('poster')) {
    		$poster = 'poster_'.time().$file->getClientOriginalName();
    		$content = @file_get_contents(public_path().'/images/tvseries/posters/'.$tvseries->poster);
    		if ($content) { 
    			unlink(public_path()."/images/tvseries/posters/".$tvseries->poster);
    		}
    		$file->move('images/tvseries/posters', $poster);
    		$input['poster'] = $poster;
    	}

    	$input['tmdb_id'] = null;

    	$tvseries->update($input);

    	if ($menus != null) {
    		if (count($menus) > 0) {
    			if (isset($tvseries->menus) && count($tvseries->menus) > 0) {
    				foreach ($tvseries->menus as $key => $value) {
    					$value->delete();
    				}
    			}
    			foreach ($menus as $key => $value) {
    				MenuVideo::create([
    					'menu_id' => $value,
    					'tv_series_id' => $tvseries->id,
    				]);
    			}
    		}
    	} else {
    		if (isset($tvseries->menus) && count($tvseries->menus) > 0) {
    			foreach ($tvseries->menus as $key => $value) {
    				$value->delete();
    			}
    		}
    	}

    	return back()->with('updated', 'Series has been updated');
    }

  /**
   * Remove the specified resource from storage.
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
  	$tvseries = TvSeries::findOrFail($id);
  	if ($tvseries->thumbnail != null) {
  		$content = @file_get_contents(public_path().'/images/tvseries/thumbnails/'.$tvseries->thumbnail);
  		if ($content) { 
  			unlink(public_path()."/images/tvseries/thumbnails/".$tvseries->thumbnail);
  		}
  	}
  	if ($tvseries->poster != null) {
  		$content = @file_get_contents(public_path().'/images/tvseries/posters/'.$tvseries->poster);
  		if ($content) { 
  			unlink(public_path()."/images/tvseries/posters/".$tvseries->poster);
  		}
  	}
  	$tvseries->delete();
  	return back()->with('deleted', 'Tv Series has been deleted');
  }


  /**
   * Season Controllers
   */
  public function show($id)
  {

  	$tv_series = TvSeries::findOrFail($id);
  	$actor_ls = Actor::pluck('name', 'id')->all();
  	$a_lans = AudioLanguage::pluck('language', 'id')->all();
  	$seasons = Season::where('tv_series_id', $id)->get();

  	return view('admin.tvseries.seasons', compact('tv_series','actor_ls', 'a_lans', 'id', 'seasons'));

  }

  public function store_seasons(Request $request) 
  {
  	ini_set('max_execution_time', 80);
  	$request->validate([
  		'season_no' => 'required'
  	]);

  	$TMDB_API_KEY = env('TMDB_API_KEY');

  	$input = $request->except('a_language', 'subtitle_list');
  	$a_lans = $request->input('a_language');

  	if ($a_lans) {
  		$a_lans = implode(',', $a_lans);
  		$input['a_language'] = $a_lans;
  	} else {
  		$input['a_language'] = null;
  	}

  	if ($request->subtitle == 1) {
  		$subtitles = $request->input('subtitle_list');
  		if ($subtitles) {
  			$subtitles = implode(',', $subtitles);
  			$input['subtitle_list'] = $subtitles;
  		} else {
  			$input['subtitle_list'] = null;
  		}
  	} else {
  		$input['subtitle_list'] = null;
  		$input['subtitle'] = 0;
  	}

  	if ($input['tmdb'] == 'Y') {

  		$tvseries_tmdb = TvSeries::findOrFail($input['tv_series_id']);

  		if ($tvseries_tmdb->tmdb_id == null) {
  			return back()->with('deleted', "Please add your Tv Series with TMDB than you can add it's seasons via TMDB");
  		}

  		if (Session::has('changed_language')) {
  			$search_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tvseries_tmdb->tmdb_id.'/season/'.$input['season_no'].'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
  		} else {
  			$search_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tvseries_tmdb->tmdb_id.'/season/'.$input['season_no'].'?api_key='.$TMDB_API_KEY);
  		}

  		if (isset($search_data)) {
  			$season_data = json_decode($search_data, true);
  		}

  		if (!isset($season_data) || $season_data == null) {
  			return back()->with('deleted', 'Tv Series does not found by tmdb servers !');
  		}

  		if ($season_data != null) {
  			$input['tmdb_id'] = $season_data['id'];
  		} else {
  			return back()->with('deleted', 'Tv Series does not found by tmdb servers !');
  		}

  		$thumbnail = null;
  		$poster = null;

  		if ($file = $request->file('thumbnail')) {
  			$thumbnail = 'thumb_'.time().$file->getClientOriginalName();
  			$file->move('images/tvseries/thumbnails', $thumbnail);
  		} else {
  			$url = $season_data['poster_path'];
  			$contents = @file_get_contents('https://image.tmdb.org/t/p/w500/'.$url);
  			$name = substr($url, strrpos($url, '/') + 1);
  			$name = 'tmdb_'.$name;
  			if ($contents) {  
  				$tmdb_img = Storage::disk('imdb_poster_tv_series')->put($name, $contents);
  				if ($tmdb_img) {
  					$thumbnail = $name;              
  				}
  			}
  		}

  		if ($file = $request->file('poster')) {
  			$poster = 'poster_'.time().$file->getClientOriginalName();
  			$file->move('images/tvseries/posters', $poster);
  		}

          // get actors and create theme
  		$tmdb_actors_id = collect();
  		$get_tmdb_actors_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tvseries_tmdb->tmdb_id.'/season/'.$input['season_no'].'/credits?api_key='.$TMDB_API_KEY);
  		if ($get_tmdb_actors_data) {
  			$get_tmdb_actors_data = json_decode($get_tmdb_actors_data, true);
  			$get_tmdb_actors_data = (object) $get_tmdb_actors_data;
  			if (count($get_tmdb_actors_data) > 0) {
  				foreach ($get_tmdb_actors_data->cast as $key => $item_act) {
  					if ($key <= 4) {

  						$check_list = Actor::where('name',$item_act['name'])->first();

  						if (!isset($check_list)) {
                    // Actor Image
  							$actor_image = null;
  							$act_image_url = $item_act['profile_path'];
  							$act_contents = @file_get_contents('https://image.tmdb.org/t/p/w500/'.$act_image_url);
  							$act_img_name = substr($act_image_url, strrpos($act_image_url, '/') + 1);
  							$act_img_name = 'tmdb_'.$act_img_name;
  							if ($act_contents) {
  								$dir_created_img = Storage::disk('actor_image_path')->put($act_img_name, $act_contents);
  								if ($dir_created_img) {
  									$actor_image = $act_img_name;              
  								}
  							}

  							$tmdb_actor = Actor::create([
  								'name' => $item_act['name'],
  								'image' => $actor_image
  							]);

  							if (isset($tmdb_actor)) {
  								$tmdb_actors_id->push($tmdb_actor->id);
  							}

  						} else {

  							$tmdb_actors_id->push($check_list->id);

  						}
  					}
  				}
  			}
  		}        
  		$tmdb_actors_id = $tmdb_actors_id->flatten();
  		$tmdb_actors_id = substr($tmdb_actors_id, 1, -1);

          // Publish Year
  		$pub_year = substr($season_data['air_date'], 0, 4);

  		Season::create([
  			'tv_series_id' => $input['tv_series_id'],
  			'tmdb_id' => $input['tmdb_id'],
  			'season_no' => $input['season_no'],
  			'tmdb' => $input['tmdb'],
  			'a_language' => $input['a_language'],
  			'subtitle' => $input['subtitle'],
  			'subtitle_list' => $input['subtitle_list'],
  			'publish_year' => (isset($pub_year) ? $pub_year : null),
  			'thumbnail' => $thumbnail,
  			'poster' => $poster,  
  			'actor_id' => $tmdb_actors_id,
  			'detail' => $season_data['overview'],
  		]);

  		return back()->with('added', 'Season has been added');

  	}

  	$actor_id = $request->input('actor_id');
  	if ($actor_id) {
  		$actor_id = implode(',', $actor_id);
  		$input['actor_id'] = $actor_id;
  	} else {
  		$input['actor_id'] = null;
  	}

  	if ($file = $request->file('thumbnail')) {
  		$thumbnail = 'thumb_'.time().$file->getClientOriginalName();
  		$file->move('images/tvseries/thumbnails', $thumbnail);
  		$input['thumbnail'] = $thumbnail;
  	}

  	if ($file = $request->file('poster')) {
  		$poster = 'poster_'.time().$file->getClientOriginalName();
  		$file->move('images/tvseries/posters', $poster);
  		$input['poster'] = $poster;
  	}

  	Season::create($input);
  	return back()->with('added', 'Season has been added');

  }

  public function update_seasons(Request $request, $id) 
  {
  	$season = Season::findOrFail($id);

  	$input = $request->all();
  	$a_lans = $request->input('a_language');

  	$request->validate([
  		'season_no' => 'required'
  	]);

  	$TMDB_API_KEY = env('TMDB_API_KEY');

  	if ($a_lans) {
  		$a_lans = implode(',', $a_lans);
  		$input['a_language'] = $a_lans;
  	} else {
  		$input['a_language'] = null;
  	}

  	if ($request->subtitle == 1) {
  		$subtitles = $request->input('subtitle_list');
  		if ($subtitles) {
  			$subtitles = implode(',', $subtitles);
  			$input['subtitle_list'] = $subtitles;
  		} else {
  			$input['subtitle_list'] = null;
  		}
  	} else {
  		$input['subtitle_list'] = null;
  		$input['subtitle'] = 0;
  	}

  	if ($input['tmdb'] == 'Y') {

  		$tvseries_tmdb = TvSeries::findOrFail($input['tv_series_id']);

  		if ($tvseries_tmdb->tmdb_id == null) {
  			return back()->with('deleted', "Please add your Tv Series with TMDB than you can add or update it's seasons via TMDB");
  		}

  		if (Session::has('changed_language')) {
  			$search_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tvseries_tmdb->tmdb_id.'/season/'.$input['season_no'].'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
  		} else {
  			$search_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tvseries_tmdb->tmdb_id.'/season/'.$input['season_no'].'?api_key='.$TMDB_API_KEY);
  		}

  		if (isset($search_data)) {
  			$season_data = json_decode($search_data, true);
  		}

  		if (isset($season_data) && $season_data == null) {
  			return back()->with('deleted', 'Tv Series does not found by tmdb servers !');
  		}

  		if ($season_data != null) {
  			$input['tmdb_id'] = $season_data['id'];
  		} else {
  			return back()->with('deleted', 'Tv Series does not found by tmdb servers !');
  		}

  		$thumbnail = null;
  		$poster = null;

  		if ($file = $request->file('thumbnail')) {
  			$thumbnail = 'thumb_'.time().$file->getClientOriginalName();
  			if ($season->thumbnail != null) {
  				$content = @file_get_contents(public_path().'/images/tvseries/thumbnails/'.$season->thumbnail);
  				if ($content) { 
  					unlink(public_path()."/images/tvseries/thumbnails/".$season->thumbnail);
  				}
  			}
  			$file->move('images/tvseries/thumbnails', $thumbnail);
  		} else {
  			$url = $season_data['poster_path'];
  			$contents = @file_get_contents('https://image.tmdb.org/t/p/w500/'.$url);
  			$name = substr($url, strrpos($url, '/') + 1);
  			$name = 'tmdb_'.$name;
  			if ($contents) {  
  				if ($season->thumbnail != null) {
  					$content = @file_get_contents(public_path().'/images/tvseries/thumbnails/'.$season->thumbnail);
  					if ($content) { 
  						unlink(public_path()."/images/tvseries/thumbnails/".$season->thumbnail);
  					}
  				}
  				$tmdb_img = Storage::disk('imdb_poster_tv_series')->put($name, $contents);
  				if ($tmdb_img) {
  					$thumbnail = $name;              
  				}
  			}
  		}

  		if ($file = $request->file('poster')) {
  			$poster = 'poster_'.time().$file->getClientOriginalName();
  			$file->move('images/tvseries/posters', $poster);
  		}

        // get actors and create theme
  		$tmdb_actors_id = collect();
  		$get_tmdb_actors_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tvseries_tmdb->tmdb_id.'/season/'.$input['season_no'].'/credits?api_key='.$TMDB_API_KEY);
  		if ($get_tmdb_actors_data) {
  			$get_tmdb_actors_data = json_decode($get_tmdb_actors_data, true);
  			$get_tmdb_actors_data = (object) $get_tmdb_actors_data;
  			if (count($get_tmdb_actors_data) > 0) {
  				foreach ($get_tmdb_actors_data->cast as $key => $item_act) {
  					if ($key <= 4) {

  						$check_list = Actor::where('name',$item_act['name'])->first();

  						if (!isset($check_list)) {
                  // Actor Image
  							$actor_image = null;
  							$act_image_url = $item_act['profile_path'];
  							$act_contents = @file_get_contents('https://image.tmdb.org/t/p/w500/'.$act_image_url);
  							$act_img_name = substr($act_image_url, strrpos($act_image_url, '/') + 1);
  							$act_img_name = 'tmdb_'.$act_img_name;
  							if ($act_contents) {
  								$dir_created_img = Storage::disk('actor_image_path')->put($act_img_name, $act_contents);
  								if ($dir_created_img) {
  									$actor_image = $act_img_name;              
  								}
  							}

  							$tmdb_actor = Actor::create([
  								'name' => $item_act['name'],
  								'image' => $actor_image
  							]);

  							if (isset($tmdb_actor)) {
  								$tmdb_actors_id->push($tmdb_actor->id);
  							}

  						} else {

  							$tmdb_actors_id->push($check_list->id);

  						}
  					}
  				}
  			}
  		}        
  		$tmdb_actors_id = $tmdb_actors_id->flatten();
  		$tmdb_actors_id = substr($tmdb_actors_id, 1, -1);

        // Publish Year
  		$pub_year = substr($season_data['air_date'], 0, 4);

  		$season->update([
  			'tv_series_id' => $input['tv_series_id'],
  			'tmdb_id' => $input['tmdb_id'],
  			'season_no' => $input['season_no'],
  			'tmdb' => $input['tmdb'],
  			'a_language' => $input['a_language'],
  			'subtitle' => $input['subtitle'],
  			'subtitle_list' => $input['subtitle_list'],
  			'publish_year' => (isset($pub_year) ? $pub_year : null),
  			'thumbnail' => $thumbnail,
  			'poster' => $poster,  
  			'actor_id' => $tmdb_actors_id,
  			'detail' => $season_data['overview'],
  		]);

  		return back()->with('added', 'Season has been updated');

  	}

  	if ($file = $request->file('thumbnail')) {
  		$thumbnail = 'thumb_'.time().$file->getClientOriginalName();
  		if ($season->thumbnail != null) {
  			$content = @file_get_contents(public_path().'/images/tvseries/thumbnails/'.$season->thumbnail);
  			if ($content) { 
  				unlink(public_path()."/images/tvseries/thumbnails/".$season->thumbnail);
  			}
  		}
  		$file->move('images/tvseries/thumbnails', $thumbnail);
  		$input['thumbnail'] = $thumbnail;
  	}

  	if ($file = $request->file('poster')) {
  		$poster = 'poster_'.time().$file->getClientOriginalName();
  		if ($season->poster != null) {
  			$content = @file_get_contents(public_path().'/images/tvseries/posters/'.$season->poster);
  			if ($content) { 
  				unlink(public_path()."/images/tvseries/posters/".$season->poster);
  			}
  		}
  		$file->move('images/tvseries/posters', $poster);
  		$input['poster'] = $poster;
  	}

  	$input['tmdb_id'] = null;

  	if ( is_array($input['actor_id']) ) {
  		$input['actor_id'] = implode(',', $input['actor_id']);
  	}

  	$season->update($input);

  	return back()->with('updated', 'Season has been updated');
  }

  public function destroy_seasons($id) {
  	$season = Season::findOrFail($id);
  	if ($season->thumbnail != null) {
  		$content = @file_get_contents(public_path().'/images/tvseries/thumbnails/'.$season->thumbnail);
  		if ($content) { 
  			unlink(public_path()."/images/tvseries/thumbnails/".$season->thumbnail);
  		}
  	}
  	if ($season->poster != null) {
  		$content = @file_get_contents(public_path().'/images/tvseries/posters/'.$season->poster);
  		if ($content) { 
  			unlink(public_path()."/images/tvseries/posters/".$season->poster);
  		}
  	}
  	$season->delete();
  	return back()->with('deleted', 'Season has been deleted');
  }


  /**
   * Episode Controllers
   */
  public function show_episodes($id) {
  	$episodes = Episode::where('seasons_id', $id)->get();
  	$a_lans = AudioLanguage::pluck('language', 'id')->all();
  	$season = Season::findOrFail($id);
  	return view('admin.tvseries.episodes', compact('episodes', 'id', 'season', 'a_lans'));
  }

  public function store_episodes(Request $request) 
  {
  	if ($request->tmdb == 'N') {
  		$request->validate([
  			'title' => 'required'
  		]);
  	} else if ($request->tmdb == 'Y') {
  		$request->validate([
  			'episode_no' => 'required'
  		]);
  	}

  	$TMDB_API_KEY = env('TMDB_API_KEY');

  	$input = $request->all();

  	$a_lans = $request->input('a_language');
  	if ($a_lans) {
  		$a_lans = implode(',', $a_lans);
  		$input['a_language'] = $a_lans;
  	} else {
  		$input['a_language'] = null;
  	}

  	$subtitles = $request->input('subtitle_list');
  	if ($subtitles) {
  		$subtitles = implode(',', $subtitles);
  		$input['subtitle_list'] = $subtitles;
  	} else {
  		$input['subtitle_list'] = null;
  	}

  	if(!isset($input['subtitle']))
  	{
  		$input['subtitle'] = 0;
  	}

  	if ($input['tmdb'] == 'Y') {

  		$tvseries_tmdb = TvSeries::findOrFail($input['tv_series_id']);
  		$season_tmdb = Season::findOrFail($input['seasons_id']);


  		if ($season_tmdb->tmdb_id == null && $tvseries_tmdb->tmdb_id == null) {
  			return back()->with('deleted', "Please add your Tv Series with TMDB than you can add or update it's seasons via TMDB");
  		}

  		if (Session::has('changed_language')) {
  			$search_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tvseries_tmdb->tmdb_id.'/season/'.$season_tmdb->season_no.'/episode/'.$input['episode_no'].'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
  		} else {
  			$search_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tvseries_tmdb->tmdb_id.'/season/'.$season_tmdb->season_no.'/episode/'.$input['episode_no'].'?api_key='.$TMDB_API_KEY);
  		}

  		if (isset($search_data)) {
  			$episode_data = json_decode($search_data, true);
  		}

  		if (!isset($episode_data) || $episode_data == null) {
  			return back()->with('deleted', 'The Episode does not found by tmdb servers !');
  		}

  		if ($episode_data != null) {
  			$input['tmdb_id'] = $episode_data['id'];
  		} else {
  			return back()->with('deleted', 'The Episode does not found by tmdb servers !');
  		}

  		if ($sub_file = $request->file('subtitle_files')) {
  			$name = 'sub'.time().$sub_file->getClientOriginalName();
  			$sub_file->move('subtitles', $name);
  			$input['subtitle_files'] = $name;
  		} else {
  			$input['subtitle_files'] = null;
  		}

  		$created_episode = Episode::create([
  			'seasons_id' => $input['seasons_id'],
  			'title' => $episode_data['name'],
  			'episode_no' => $input['episode_no'],
  			'tmdb' => $input['tmdb'],
  			'tmdb_id' => $input['tmdb_id'],
  			'subtitle' => $input['subtitle'],
  			'subtitle_list' => $input['subtitle_list'],
  			'subtitle_files' => $input['subtitle_files'],
  			'a_language' => $input['a_language'],
  			'duration' => $input['duration'],
  			'detail' => $episode_data['overview'],
  			'released' => $episode_data['air_date']
  		]);

  		if ($request->ready_url_check == 1) {

  			VideoLink::create([
  				'episode_id' => $created_episode->id,
  				'ready_url' => $input['ready_url'],
  				'url_360' => null,
  				'url_480' => null,
  				'url_720' => null,
  				'url_1080' => null
  			]);

  		} else {

  			VideoLink::create([
  				'episode_id' => $created_episode->id,
  				'ready_url' => null,
  				'url_360' => $input['url_360'],
  				'url_480' => $input['url_480'],
  				'url_720' => $input['url_720'],
  				'url_1080' => $input['url_1080'],
  			]);

  		}

  		return back()->with('added', 'Episode has been added');

  	}

  	if ($sub_file = $request->file('subtitle_files')) {
  		$name = 'sub'.time().$sub_file->getClientOriginalName();
  		$sub_file->move('subtitles', $name);
  		$input['subtitle_files'] = $name;
  	} else {
  		$input['subtitle_files'] = null;
  	}

  	$input['tmdb_id'] = null;
  	$created_episode = Episode::create($input);

  	if ($request->ready_url_check == 1) {

  		VideoLink::create([
  			'episode_id' => $created_episode->id,
  			'ready_url' => $input['ready_url'],
  			'url_360' => null,
  			'url_480' => null,
  			'url_720' => null,
  			'url_1080' => null
  		]);

  	} else {

  		VideoLink::create([
  			'episode_id' => $created_episode->id,
  			'ready_url' => null,
  			'url_360' => $input['url_360'],
  			'url_480' => $input['url_480'],
  			'url_720' => $input['url_720'],
  			'url_1080' => $input['url_1080'],
  		]);

  	}

  	return back()->with('added', 'Episode has been added');
  }

  public function update_episodes(Request $request, $id) 
  {
  	$main_episode = Episode::findOrFail($id);

  	if ($request->tmdb == 'N') {
  		$request->validate([
  			'title' => 'required'
  		]);
  	} else if ($request->tmdb == 'Y') {
  		$request->validate([
  			'episode_no' => 'required'
  		]);
  	}

  	$TMDB_API_KEY = env('TMDB_API_KEY');

  	$input = $request->all();

  	$a_lans = $request->input('a_language');
  	if ($a_lans) {
  		$a_lans = implode(',', $a_lans);
  		$input['a_language'] = $a_lans;
  	} else {
  		$input['a_language'] = null;
  	}

  	$subtitles = $request->input('subtitle_list');
  	if ($subtitles) {
  		$subtitles = implode(',', $subtitles);
  		$input['subtitle_list'] = $subtitles;
  	} else {
  		$input['subtitle_list'] = null;
  	}

  	if(!isset($input['subtitle']))
  	{
  		$input['subtitle'] = 0;
  	}

  	if ($input['tmdb'] == 'Y') {

  		$tvseries_tmdb = TvSeries::findOrFail($input['tv_series_id']);
  		$season_tmdb = Season::findOrFail($input['seasons_id']);

  		if ($season_tmdb->tmdb_id == null && $tvseries_tmdb->tmdb_id == null) {
  			return back()->with('deleted', "Please add your Tv Series with TMDB than you can add or update it's seasons via TMDB");
  		}

  		if (Session::has('changed_language')) {
  			$search_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tvseries_tmdb->tmdb_id.'/season/'.$season_tmdb->season_no.'/episode/'.$input['episode_no'].'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
  		} else {
  			$search_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tvseries_tmdb->tmdb_id.'/season/'.$season_tmdb->season_no.'/episode/'.$input['episode_no'].'?api_key='.$TMDB_API_KEY);
  		}

  		if (isset($search_data)) {
  			$episode_data = json_decode($search_data, true);
  		}

  		if (!isset($episode_data) || $episode_data == null) {
  			return back()->with('deleted', 'The Episode does not found by tmdb servers !');
  		}

  		if ($episode_data != null) {
  			$input['tmdb_id'] = $episode_data['id'];
  		} else {
  			return back()->with('deleted', 'The Episode does not found by tmdb servers !');
  		}

  		if ($sub_file = $request->file('subtitle_files')) {
  			$name = 'sub'.time().$sub_file->getClientOriginalName();
  			if ($main_episode->subtitle_files != null) {
  				$content = @file_get_contents(public_path().'/subtitles/'.$main_episode->subtitle_files);
  				if ($content) { 
  					unlink(public_path()."/subtitles/".$main_episode->subtitle_files);
  				}
  			}
  			$sub_file->move('subtitles', $name);
  			$input['subtitle_files'] = $name;
  		} else {
  			$input['subtitle_files'] = $main_episode->subtitle_files;
  		}

  		$main_episode->update([
  			'seasons_id' => $input['seasons_id'],
  			'title' => $episode_data['name'],
  			'episode_no' => $input['episode_no'],
  			'tmdb' => $input['tmdb'],
  			'tmdb_id' => $input['tmdb_id'],
  			'subtitle' => $input['subtitle'],
  			'subtitle_list' => $input['subtitle_list'],
  			'subtitle_files' => $input['subtitle_files'],
  			'a_language' => $input['a_language'],
  			'duration' => $input['duration'],
  			'detail' => $episode_data['overview'],
  			'released' => $episode_data['air_date']
  		]);

  		if (isset($main_episode->video_link)) {

  			if ($request->ready_url_check == 1) {

  				$main_episode->video_link->update([
  					'ready_url' => $input['ready_url'],
  					'url_360' => null,
  					'url_480' => null,
  					'url_720' => null,
  					'url_1080' => null
  				]);

  			} else {

  				$main_episode->video_link->update([
  					'ready_url' => null,
  					'url_360' => $input['url_360'],
  					'url_480' => $input['url_480'],
  					'url_720' => $input['url_720'],
  					'url_1080' => $input['url_1080']
  				]);

  			}

  		} else {

  			if ($request->ready_url_check == 1) {

  				VideoLink::create([
  					'episode_id' => $main_episode->id,
  					'ready_url' => $input['ready_url'],
  					'url_360' => null,
  					'url_480' => null,
  					'url_720' => null,
  					'url_1080' => null
  				]);

  			} else {

  				VideoLink::create([
  					'episode_id' => $main_episode->id,
  					'ready_url' => null,
  					'url_360' => $input['url_360'],
  					'url_480' => $input['url_480'],
  					'url_720' => $input['url_720'],
  					'url_1080' => $input['url_1080'],
  				]);

  			}

  		}

  		return back()->with('updated', 'Episode has been updated');

  	}

  	$input['tmdb_id'] = null;

  	if ($sub_file = $request->file('subtitle_files')) {
  		$name = 'sub'.time().$sub_file->getClientOriginalName();
  		if ($main_episode->subtitle_files != null) {
  			$content = @file_get_contents(public_path().'/subtitles/'.$main_episode->subtitle_files);
  			if ($content) { 
  				unlink(public_path()."/subtitles/".$main_episode->subtitle_files);
  			}
  		}
  		$sub_file->move('subtitles', $name);
  		$input['subtitle_files'] = $name;
  	} else {
  		$input['subtitle_files'] = $main_episode->subtitle_files;
  	}

  	$main_episode->update($input);

  	if (isset($main_episode->video_link)) {

  		if ($request->ready_url_check == 1) {

  			$main_episode->video_link->update([
  				'ready_url' => $input['ready_url'],
  				'url_360' => null,
  				'url_480' => null,
  				'url_720' => null,
  				'url_1080' => null
  			]);

  		} else {

  			$main_episode->video_link->update([
  				'ready_url' => null,
  				'url_360' => $input['url_360'],
  				'url_480' => $input['url_480'],
  				'url_720' => $input['url_720'],
  				'url_1080' => $input['url_1080']
  			]);

  		}

  	} else {

  		if ($request->ready_url_check == 1) {

  			VideoLink::create([
  				'episode_id' => $main_episode->id,
  				'ready_url' => $input['ready_url'],
  				'url_360' => null,
  				'url_480' => null,
  				'url_720' => null,
  				'url_1080' => null
  			]);

  		} else {

  			VideoLink::create([
  				'episode_id' => $main_episode->id,
  				'ready_url' => null,
  				'url_360' => $input['url_360'],
  				'url_480' => $input['url_480'],
  				'url_720' => $input['url_720'],
  				'url_1080' => $input['url_1080'],
  			]);

  		}

  	}

  	return back()->with('updated', 'Episode has been updated');
  }

  public function destroy_episodes($id) {
  	$episode = Episode::findOrFail($id);
  	if ($episode->subtitle_files != null) {
  		$content = @file_get_contents(public_path().'/subtitles/'.$episode->subtitle_files);
  		if ($content) { 
  			unlink(public_path()."/subtitles/".$episode->subtitle_files);
  		}
  	}
  	$episode->delete();
  	return back()->with('deleted', 'Episode has been deleted');
  }

  public function bulk_delete(Request $request)
  {
  	$validator = Validator::make($request->all(), [
  		'checked' => 'required',
  	]);

  	if ($validator->fails()) {

  		return back()->with('deleted', 'Please check one of them to delete');
  	}

  	foreach ($request->checked as $checked) {

  		$tvseries = TvSeries::findOrFail($checked);

  		if ($tvseries->thumbnail != null) {
  			$content = @file_get_contents(public_path().'/images/tvseries/thumbnails/'.$tvseries->thumbnail);
  			if ($content) { 
  				unlink(public_path()."/images/tvseries/thumbnails/".$tvseries->thumbnail);
  			}
  		}
  		if ($tvseries->poster != null) {
  			$content = @file_get_contents(public_path().'/images/tvseries/posters/'.$tvseries->poster);
  			if ($content) { 
  				unlink(public_path()."/images/tvseries/posters/".$tvseries->poster);
  			}
  		}

  		TvSeries::destroy($checked);
  	}

  	return back()->with('deleted', 'Tv Shows has been deleted');   
  }

     /**
     * Translate the specified resource from storage.
     * Translate all tmdb movies on one click
     * @return \Illuminate\Http\Response
     */
     public function tmdb_translations()
     {
     	ini_set('max_execution_time', 3000);
     	$all_tv = TvSeries::where('tmdb', 'Y')->get();
     	$TMDB_API_KEY = env('TMDB_API_KEY');

     	if ($TMDB_API_KEY == null || $TMDB_API_KEY == '') {
     		return back()->with('deleted', 'Please provide your TMDB api key to translate');
     	}

     	if (isset($all_tv) && count($all_tv) > 0) {
     		foreach ($all_tv as $key => $tv) {
     			if (Session::has('changed_language')) {
     				$fetch_tv = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tv->tmdb_id.'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
     			} else {
     				return back()->with('updated', 'Please Choose a language by admin panel top right side language menu');
     			}

     			$tmdb_tv = json_decode($fetch_tv, true);
     			if (isset($tmdb_tv) && $tmdb_tv != null) {
     				$tv->update([
     					'detail' => $tmdb_tv['overview']
     				]);
     			}

     			if (isset($tv->seasons) && count($tv->seasons) > 0) {
     				foreach ($tv->seasons as $season) {
     					if ($season->tmdb == 'Y') {
     						$search_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tv->tmdb_id.'/season/'.$season->season_no.'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
     						if (isset($search_data)) {
     							$season_data = json_decode($search_data, true);
     						}
     						if (isset($season_data) && $season_data != null) {
     							$season->update([
     								'detail' => $season_data['overview']
     							]);
     						}
     						if (isset($season->episodes) && count($season->episodes) > 0) {
     							foreach ($season->episodes as $episode) {
     								if ($episode->tmdb == 'Y') {
     									$search_data = @file_get_contents('https://api.themoviedb.org/3/tv/'.$tv->tmdb_id.'/season/'.$season->season_no.'/episode/'.$episode->episode_no.'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
     									if (isset($search_data)) {
     										$episode_data = json_decode($search_data, true);
     									}
     									if (isset($episode_data) && $episode_data != null) {
     										$episode->update([
     											'detail' => $episode_data['overview']
     										]);
     									}
     								}
     							}
     						}
     					}
     				}
     			}
     		}
     		return back()->with('added', 'All TvSeries and its seasons and episodes (only by TMDB) has been translated');
     	} else {
     		return back()->with('updated', 'Please create at least one tvseries by TMDB option to translate');
     	}
     } 

 }
