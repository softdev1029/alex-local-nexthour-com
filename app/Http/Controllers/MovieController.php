<?php

namespace App\Http\Controllers;

use App\Actor;
use App\MenuVideo;
use App\Menu;
use App\AudioLanguage;
use App\Director;
use App\Genre;
use App\Movie;
use App\MovieSeries;
use App\Videolink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use ImageOptimizer;

class MovieController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = Menu::all();

        $director_ls = Director::pluck('name', 'id')->all();
        $actor_ls = Actor::pluck('name', 'id')->all();
        $genre_ls = Genre::pluck('name', 'id')->all();
        $a_lans = AudioLanguage::pluck('language', 'id')->all();

        $all_movies = Movie::all();
        $series_list = MovieSeries::all();
        $movie_list_exc_series = collect();
        $movie_list_with_only_series = collect();
        if (count($series_list) > 0) {
          foreach ($series_list as $item) {
            $series = Movie::where('id', $item->series_movie_id)->first();
            $movie_list_with_only_series->push($series);
          }
          $movie_list_exc_series = $all_movies->diff($movie_list_with_only_series);
          $movie_list_exc_series = $movie_list_exc_series->flatten()->pluck('title', 'id');
          $movie_list_exc_series = json_decode($movie_list_exc_series, true);
        } else {
          $movie_list_exc_series = Movie::pluck('title', 'id')->all();
        }

        return view('admin.movie.create', compact('menus', 'director_ls','a_lans', 'director_ls', 'actor_ls', 'genre_ls', 'movie_list_exc_series'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request
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

        $input = $request->except('a_language', 'subtitle_list', 'movie_id');
        $TMDB_API_KEY = env('TMDB_API_KEY');

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

        if ($input['tmdb'] != 'Y') {
          $request->validate([
              'genre_id' => 'required'
          ]);
        }

        if(!isset($input['subtitle']))
        {
          $input['subtitle'] = 0;
        }
        if(!isset($input['featured']))
        {
          $input['featured'] = 0;
        }
        if(!isset($input['series']))
        {
          $input['series'] = 0;
        }
        
        if ($input['tmdb'] == 'Y') {

          if ($TMDB_API_KEY == null || $TMDB_API_KEY == '') {
            return back()->with('deleted', 'Please provide your TMDB api key or add movie by custom fields');
          }

          $title = urlencode($input['title']);
          $search_data = @file_get_contents('https://api.themoviedb.org/3/search/movie?api_key='.$TMDB_API_KEY.'&query='.$title);

          if ($search_data) {
            $data = json_decode($search_data, true);
          }

          if (isset($data) && $data['results'] == null) {
            return back()->with('deleted', 'Movie does not found by tmdb servers !');
          }

          if (Session::has('changed_language')) {
            $fetch_movie = @file_get_contents('https://api.themoviedb.org/3/movie/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
            $fetch_movie_for_genres = @file_get_contents('https://api.themoviedb.org/3/movie/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
          } else {
            $fetch_movie = @file_get_contents('https://api.themoviedb.org/3/movie/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
            $fetch_movie_for_genres = @file_get_contents('https://api.themoviedb.org/3/movie/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
          }

          if (!$fetch_movie && !$fetch_movie_for_genres) {
            return back()->with('deleted', 'Movie does not found by tmdb servers !');
          }

          $tmdb_movie = json_decode($fetch_movie, true);
          // Only for genres
          $tmdb_movie_for_genres = json_decode($fetch_movie_for_genres, true);

          if ($tmdb_movie != null) {
            $input['tmdb_id'] = $tmdb_movie['id'];
          } else {
            return back()->with('deleted', 'Movie does not found by tmdb servers !');
          }

          if (!isset($input['trailer_url']) && $tmdb_movie != null && $TMDB_API_KEY != null)
          {

            if ($this->get_http_response_code('https://api.themoviedb.org/3/movie/'.$input['tmdb_id'].'/videos?api_key='.$TMDB_API_KEY) != "200") {
              $input['trailer_url'] = null;
            } else {
              $tmdb_trailers = @file_get_contents('https://api.themoviedb.org/3/movie/'.$input['tmdb_id'].'/videos?api_key='.$TMDB_API_KEY);
              if ($tmdb_trailers) {
                $tmdb_trailers = json_decode($tmdb_trailers, true);
                if (isset($tmdb_trailers) && count($tmdb_trailers['results']) > 0) {
                  $input['trailer_url'] = 'https://youtu.be/'.$tmdb_trailers['results'][0]['key'];
                }
              } else {
                $input['trailer_url'] = null;
              }
            }
          }

          $thumbnail = null;
          $poster = null;

          if ($file = $request->file('thumbnail')) {

            $thumbnail = 'thumb_'.time().$file->getClientOriginalName();
            $file->move('images/movies/thumbnails', $thumbnail);

          } else {

            $url = $tmdb_movie['poster_path'];
            $contents = @file_get_contents('https://image.tmdb.org/t/p/w500/'.$url);
            $name = substr($url, strrpos($url, '/') + 1);
            $name = 'tmdb_'.$name;
            if ($contents) {  
              $tmdb_img = Storage::disk('imdb_poster_movie')->put($name, $contents);
              if ($tmdb_img) {
                $thumbnail = $name;              
              }
            }
          }

          if ($file = $request->file('poster')) {
            $poster = 'poster_'.time().$file->getClientOriginalName();
            $file->move('images/movies/posters', $poster);
          } else {

            $url_2 = $tmdb_movie['backdrop_path'];
            $contents_2 = @file_get_contents('https://image.tmdb.org/t/p/original/'.$url_2);
            $name_2 = substr($url_2, strrpos($url_2, '/') + 1);
            $name_2 = 'tmdb_'.$name_2;
            if ($contents_2) {
              $tmdb_img_2 = Storage::disk('imdb_backdrop_movie')->put($name_2, $contents_2);
              if ($tmdb_img_2) {
                $poster = $name_2;              
              }
            }
          }

          // Get Directors and create theme
          $tmdb_directors_id = collect();
          $get_tmdb_director_data = @file_get_contents('https://api.themoviedb.org/3/movie/'.$tmdb_movie['id'].'/credits?api_key='.$TMDB_API_KEY);
          if ($get_tmdb_director_data) {
            $get_tmdb_director_data = json_decode($get_tmdb_director_data, true);
            $get_tmdb_director_data = (object) $get_tmdb_director_data;
            foreach ($get_tmdb_director_data->crew as $key => $item_dir) {
              if ($key <= 4) {
                if ($item_dir['department'] == 'Directing') {


                  $check_list = Director::where('name',$item_dir['name'])->first();

                  if (!isset($check_list)) {

                    // Director Image
                    $director_image = null;
                    $dir_image_url = $item_dir['profile_path'];
                    $dir_contents = @file_get_contents('https://image.tmdb.org/t/p/w500/'.$dir_image_url);
                    $dir_img_name = substr($dir_image_url, strrpos($dir_image_url, '/') + 1);
                    $dir_img_name = 'tmdb_'.$dir_img_name;
                    if ($dir_contents) {
                      $dir_created_img = Storage::disk('director_image_path')->put($dir_img_name, $dir_contents);
                      if ($dir_created_img) {
                        $director_image = $dir_img_name;              
                      }
                    }

                    $tmdb_director = Director::create([
                      'name' => $item_dir['name'],
                      'image' => $director_image
                    ]);

                    if (isset($tmdb_director)) {
                      $tmdb_directors_id->push($tmdb_director->id);
                    }

                    
                  } else {
                    $tmdb_directors_id->push($check_list->id);
                  }
                }
              }  
            }
          }

          $tmdb_directors_id = $tmdb_directors_id->flatten();

          // get actors and create theme
          $tmdb_actors_id = collect();
          $get_tmdb_actors_data = @file_get_contents('https://api.themoviedb.org/3/movie/'.$tmdb_movie['id'].'/credits?api_key='.$TMDB_API_KEY);
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

          // get Genres and create theme
          $tmdb_genres_id = collect();
          if (isset($tmdb_movie_for_genres) && $tmdb_movie_for_genres != null) {
            foreach ($tmdb_movie_for_genres['genres'] as $tmdb_genre) {

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

          if ($sub_file = $request->file('subtitle_files')) {
            $name = 'sub'.time().$sub_file->getClientOriginalName();
            $sub_file->move('subtitles', $name);
            $input['subtitle_files'] = $name;
          } else {
            $input['subtitle_files'] = null;
          }

          $publish_year = substr($tmdb_movie['release_date'], 0, 4);
          $tmdb_directors_id = substr($tmdb_directors_id, 1, -1);
          $tmdb_actors_id = substr($tmdb_actors_id, 1, -1);
          $tmdb_genres_id = substr($tmdb_genres_id, 1, -1);

          $created_movie = Movie::create([
            'title' => $input['title'],
            'tmdb_id' => $tmdb_movie['id'],
            'duration' => $tmdb_movie['runtime'],
            'tmdb' => $input['tmdb'],
            'director_id' => $tmdb_directors_id,
            'actor_id' => $tmdb_actors_id,
            'genre_id' => $tmdb_genres_id,
            'trailer_url' => $input['trailer_url'],
            'subtitle' => $input['subtitle'],
            'subtitle_list' => $input['subtitle_list'],
            'subtitle_files' => $input['subtitle_files'],
            'featured' => $input['featured'],
            'series' => $input['series'],
            'detail' => $tmdb_movie['overview'],
            'rating' => $tmdb_movie['vote_average'],
            'publish_year' => $publish_year,
            'released' => $tmdb_movie['release_date'],
            'maturity_rating' => $input['maturity_rating'],
            'a_language' => $input['a_language'],
            'thumbnail' => $thumbnail,
            'poster' => $poster
          ]);

          if ($input['series'] == 1) {
            MovieSeries::create([
              'movie_id' => $request->movie_id,
              'series_movie_id' => $created_movie->id
            ]);
          }

          if ($request->ready_url_check == 1) {

            VideoLink::create([
              'movie_id' => $created_movie->id,
              'ready_url' => $input['ready_url']
            ]);
            
          } else {

            VideoLink::create([
              'movie_id' => $created_movie->id,
              'url_360' => $input['url_360'],
              'url_480' => $input['url_480'],
              'url_720' => $input['url_720'],
              'url_1080' => $input['url_1080'],
            ]);

          }

          if ($menus != null) {
            if (count($menus) > 0) {
              foreach ($menus as $key => $value) {
                MenuVideo::create([
                  'menu_id' => $value,
                  'movie_id' => $created_movie->id,
                ]);
              }
            }
          }

          return back()->with('added', 'Movie has been added');
        }

        $director_ids = $request->input('director_id');
        if ($director_ids) {
          $director_ids = implode(',', $director_ids);
          $input['director_id'] = $director_ids;
        } else {
          $input['director_id'] = null;
        }

        $actor_ids = $request->input('actor_id');
        if ($actor_ids) {
          $actor_ids = implode(',', $actor_ids);
          $input['actor_id'] = $actor_ids;
        } else {
          $input['actor_id'] = null;
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
          $file->move('images/movies/thumbnails', $thumbnail);
          $input['thumbnail'] = $thumbnail;
        }

        if ($file = $request->file('poster')) {
          $poster = 'poster_'.time().$file->getClientOriginalName();
          $file->move('images/movies/posters', $poster);
          $input['poster'] = $poster;
        }

        $created_movie = Movie::create($input);

        if ($input['series'] == 1) {
          MovieSeries::create([
              'movie_id' => $request->movie_id,
              'series_movie_id' => $created_movie->id
          ]);
        }

        if ($request->ready_url_check == 1) {

          VideoLink::create([
            'movie_id' => $created_movie->id,
            'ready_url' => $input['ready_url']
          ]);
          
        } else {

          VideoLink::create([
            'movie_id' => $created_movie->id,
            'url_360' => $input['url_360'],
            'url_480' => $input['url_480'],
            'url_720' => $input['url_720'],
            'url_1080' => $input['url_1080'],
          ]);

        }

        if ($menus != null) {
          if (count($menus) > 0) {
            foreach ($menus as $key => $value) {
              MenuVideo::create([
                'menu_id' => $value,
                'movie_id' => $created_movie->id,
              ]);
            }
          }
        }

        return back()->with('added', 'Movie has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $url
     * @return \Illuminate\Http\Response
     */
    public function get_http_response_code($url) {
      $headers = get_headers($url);
      return substr($headers[0], 9, 3);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menus = Menu::all();
        $director_ls = Director::all();
        $actor_ls = Actor::all();
        $genre_ls = Genre::all();
        $all_languages = AudioLanguage::all();
        $movie = Movie::findOrFail($id);

        $all_movies = Movie::all();
        $series_list = MovieSeries::all();
        $movie_list_exc_series = collect();
        $movie_list_with_only_series = collect();
        if (count($series_list) > 0) {
          foreach ($series_list as $item) {
            $series = Movie::where('id', $item->series_movie_id)->first();
            $movie_list_with_only_series->push($series);
          }
          $movie_list_exc_series = $all_movies->diff($movie_list_with_only_series);
          $movie_list_exc_series = $movie_list_exc_series->flatten()->pluck('title', 'id');
          $movie_list_exc_series = json_decode($movie_list_exc_series, true);
        } else {
          $movie_list_exc_series = Movie::pluck('title', 'id')->all();
        }

        // get old audio language values 
        $old_lans = collect();
        $a_lans = collect();
        if ($movie->a_language != null){
          $old_list = explode(',', $movie->a_language);
          for ($i = 0; $i < count($old_list); $i++) {
            $old = AudioLanguage::find(trim($old_list[$i]));
            if (isset($old)) {
              $old_lans->push($old);
            }
          }
        }
        $a_lans = $a_lans->filter(function($value, $key) {
          return  $value != null;
        });
        $a_lans = $all_languages->diff($old_lans);

        // get old subtitle language values
        $old_subtitles = collect();
        $a_subs = collect();
        if ($movie->subtitle == 1) {
          if ($movie->subtitle_list != null){
            $old_list = explode(',', $movie->subtitle_list);
            for ($i = 0; $i < count($old_list); $i++) {
              $old2 = AudioLanguage::find(trim($old_list[$i]));
              if (isset($old2)) {
                $old_subtitles->push($old2);
              }
            }
          }
        }
        $a_subs = $a_subs->filter(function($value, $key) {
          return  $value != null;
        });
        $a_subs = $all_languages->diff($old_subtitles);
          
        // get old director list 
        $old_director = collect();
        if ($movie->director_id != null){
          $old_list = explode(',', $movie->director_id);
          for ($i = 0; $i < count($old_list); $i++) {
            $old3 = Director::find(trim($old_list[$i]));
            if (isset($old3)) {
              $old_director->push($old3);
            }
          }
        }
        $director_ls = $director_ls->filter(function($value, $key) {
          return  $value != null;
        });
        $director_ls = $director_ls->diff($old_director);

        // get old actor list 
        $old_actor = collect();
        if ($movie->actor_id != null){
          $old_list = explode(',', $movie->actor_id);
          for ($i = 0; $i < count($old_list); $i++) {
            $old4 = Actor::find(trim($old_list[$i]));
            if (isset($old4)) {
              $old_actor->push($old4);
            }
          }
        }
        $old_actor = $old_actor->filter(function($value, $key) {
          return  $value != null;
        });
        $actor_ls = $actor_ls->diff($old_actor);

        // get old genre list 
        $old_genre = collect();
        if ($movie->genre_id != null){
          $old_list = explode(',', $movie->genre_id);
          for ($i = 0; $i < count($old_list); $i++) {
            $old5 = Genre::find(trim($old_list[$i]));
            if (isset($old5)) {
              $old_genre->push($old5);
            }
          }
        }
        $genre_ls = $genre_ls->filter(function($value, $key) {
          return  $value != null;
        });
        
        $genre_ls = $genre_ls->diff($old_genre);

        $this_movie_series = MovieSeries::where('series_movie_id', $id)->get();
        if (count($this_movie_series) > 0) {
          $this_movie_series_detail = Movie::where('id',$this_movie_series[0]->movie_id)->get();
        }

        return view('admin.movie.edit', compact('movie', 'director_ls', 'actor_ls', 'genre_ls', 'movie_list_exc_series', 'this_movie_series_detail', 'a_lans', 'old_lans', 'a_subs', 'old_subtitles', 'old_director', 'old_actor', 'old_genre', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        ini_set('max_execution_time', 60);
        $request->validate([
          'title' => 'required'
        ]);

        $menus = null;

        if (isset($request->menu) && count($request->menu) > 0) {
          $menus = $request->menu;
        }

        $input = $request->except('a_language', 'director_id', 'actor_id', 'genre_id', 'subtitle_list', 'movie_id');

        $TMDB_API_KEY = env('TMDB_API_KEY');

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

        if ($input['tmdb'] != 'Y') {
          $request->validate([
              'genre_id' => 'required'
          ]);
        }

        if(!isset($input['subtitle']))
        {
          $input['subtitle'] = 0;
        }
        if(!isset($input['featured']))
        {
          $input['featured'] = 0;
        }
        if(!isset($input['series']))
        {
          $input['series'] = 0;
        }

        $movie = Movie::findOrFail($id); 

        if ($input['tmdb'] == 'Y') {

          if ($TMDB_API_KEY == null || $TMDB_API_KEY == '') {
            return back()->with('deleted', 'Please provide your TMDB api key or add movie by custom fields');
          }

          $title = urlencode($input['title']);
          $search_data = @file_get_contents('https://api.themoviedb.org/3/search/movie?api_key='.$TMDB_API_KEY.'&query='.$title);

          if ($search_data) {
            $data = json_decode($search_data, true);
          }

          if (isset($data) && $data['results'] == null) {
            return back()->with('deleted', 'Movie does not found by tmdb servers !');
          }

          if (Session::has('changed_language')) {
            $fetch_movie = @file_get_contents('https://api.themoviedb.org/3/movie/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
            $fetch_movie_for_genres = @file_get_contents('https://api.themoviedb.org/3/movie/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
          } else {
            $fetch_movie = @file_get_contents('https://api.themoviedb.org/3/movie/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
            $fetch_movie_for_genres = @file_get_contents('https://api.themoviedb.org/3/movie/'.$data['results'][0]['id'].'?api_key='.$TMDB_API_KEY);
          }

          if (!$fetch_movie && !$fetch_movie_for_genres) {
            return back()->with('deleted', 'Movie does not found by tmdb servers !');
          }

          $tmdb_movie = json_decode($fetch_movie, true);
          // Only for genres
          $tmdb_movie_for_genres = json_decode($fetch_movie_for_genres, true);

          if ($tmdb_movie != null) {
            $input['tmdb_id'] = $tmdb_movie['id'];
          } else {
            return back()->with('deleted', 'Movie does not found by tmdb servers !');
          }

          if (!isset($input['trailer_url']) && $tmdb_movie != null && $TMDB_API_KEY != null)
          {
            if ($this->get_http_response_code('https://api.themoviedb.org/3/movie/'.$input['tmdb_id'].'/videos?api_key='.$TMDB_API_KEY) != "200") {
              $input['trailer_url'] = null;
            } else {
              $tmdb_trailers = @file_get_contents('https://api.themoviedb.org/3/movie/'.$input['tmdb_id'].'/videos?api_key='.$TMDB_API_KEY);
              if ($tmdb_trailers) {
                $tmdb_trailers = json_decode($tmdb_trailers, true);
                if ($tmdb_trailers['results'][0] != null) {
                  $input['trailer_url'] = 'https://youtu.be/'.$tmdb_trailers['results'][0]['key'];
                }
              } else {
                $input['trailer_url'] = null;
              }
            }
          }

          $thumbnail = null;
          $poster = null;

          if ($file = $request->file('thumbnail')) {

            $thumbnail = 'thumb_'.time().$file->getClientOriginalName();
            if ($movie->thumbnail != null) {
              $content = @file_get_contents(public_path().'/images/movies/thumbnails/'.$movie->thumbnail);
              if ($content) { 
                unlink(public_path()."/images/movies/thumbnails/".$movie->thumbnail);
              }
            }
            $file->move('images/movies/thumbnails', $thumbnail);

          } else {

            $url = $tmdb_movie['poster_path'];
            $contents = @file_get_contents('https://image.tmdb.org/t/p/w500/'.$url);
            $name = substr($url, strrpos($url, '/') + 1);
            $name = 'tmdb_'.$name;
            if ($contents) {  
              $tmdb_img = Storage::disk('imdb_poster_movie')->put($name, $contents);
              if ($tmdb_img) {
                $thumbnail = $name;              
              }
            }
          }

          if ($file = $request->file('poster')) {
            $poster = 'poster_'.time().$file->getClientOriginalName();
            if ($movie->poster != null) {
              $content = @file_get_contents(public_path().'/images/movies/posters/'.$movie->poster);
              if ($content) { 
                unlink(public_path()."/images/movies/posters/".$movie->poster);
              }
            }
            $file->move('images/movies/posters', $poster);
          } else {

            $url_2 = $tmdb_movie['backdrop_path'];
            $contents_2 = @file_get_contents('https://image.tmdb.org/t/p/original/'.$url_2);
            $name_2 = substr($url_2, strrpos($url_2, '/') + 1);
            $name_2 = 'tmdb_'.$name_2;
            if ($contents_2) {
              $tmdb_img_2 = Storage::disk('imdb_backdrop_movie')->put($name_2, $contents_2);
              if ($tmdb_img_2) {
                $poster = $name_2;              
              }
            }
          }

          // Get Directors and create theme
          $tmdb_directors_id = collect();
          $get_tmdb_director_data = @file_get_contents('https://api.themoviedb.org/3/movie/'.$tmdb_movie['id'].'/credits?api_key='.$TMDB_API_KEY);
          if ($get_tmdb_director_data) {
            $get_tmdb_director_data = json_decode($get_tmdb_director_data, true);
            $get_tmdb_director_data = (object) $get_tmdb_director_data;
            foreach ($get_tmdb_director_data->crew as $key => $item_dir) {
              if ($key <= 4) {
                if ($item_dir['department'] === 'Directing') {

                  $check_list = Director::where('name',$item_dir['name'])->first();

                  if (!isset($check_list)) {

                    // Director Image
                    $director_image = null;
                    $dir_image_url = $item_dir['profile_path'];
                    $dir_contents = @file_get_contents('https://image.tmdb.org/t/p/w500/'.$dir_image_url);
                    $dir_img_name = substr($dir_image_url, strrpos($dir_image_url, '/') + 1);
                    $dir_img_name = 'tmdb_'.$dir_img_name;
                    if ($dir_contents) {
                      $dir_created_img = Storage::disk('director_image_path')->put($dir_img_name, $dir_contents);
                      if ($dir_created_img) {
                        $director_image = $dir_img_name;              
                      }
                    }

                    $tmdb_director = Director::create([
                      'name' => $item_dir['name'],
                      'image' => $director_image
                    ]);

                    if (isset($tmdb_director)) {
                      $tmdb_directors_id->push($tmdb_director->id);
                    }

                    
                  } else {
                    $tmdb_directors_id->push($check_list->id);
                  }
                }
              }  
            }
          }
          $tmdb_directors_id = $tmdb_directors_id->flatten();

          // get actors and create theme
          $tmdb_actors_id = collect();
          $get_tmdb_actors_data = @file_get_contents('https://api.themoviedb.org/3/movie/'.$tmdb_movie['id'].'/credits?api_key='.$TMDB_API_KEY);
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

          // get Genres and create theme
          $tmdb_genres_id = collect();
          if (isset($tmdb_movie_for_genres) && $tmdb_movie_for_genres != null) {
            foreach ($tmdb_movie_for_genres['genres'] as $tmdb_genre) {

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

          if ($sub_file = $request->file('subtitle_files')) {
            $name = 'sub'.time().$sub_file->getClientOriginalName();
            if ($movie->subtitle_files != null) {
              $content = @file_get_contents(public_path().'/subtitles/'.$movie->subtitle_files);
              if ($content) { 
                unlink(public_path()."/subtitles/".$movie->subtitle_files);
              }
            }
            $sub_file->move('subtitles', $name);
            $input['subtitle_files'] = $name;
          }

          $publish_year = substr($tmdb_movie['release_date'], 0, 4);
          $tmdb_directors_id = substr($tmdb_directors_id, 1, -1);
          $tmdb_actors_id = substr($tmdb_actors_id, 1, -1);
          $tmdb_genres_id = substr($tmdb_genres_id, 1, -1);

          if ($input['series'] == 1 && $movie->series == 1) {
            $movie_series = MovieSeries::where('series_movie_id', $movie->id);
            $movie_series->update([
                'movie_id' => $request->movie_id,
                'series_movie_id' => $movie->id
            ]);
          }

          if ($input['series'] == 1 && $movie->series != 1) {
            MovieSeries::create([
              'movie_id' => $request->movie_id,
              'series_movie_id' => $movie->id
            ]);
          }

          $movie->update([
            'title' => $input['title'],
            'tmdb_id' => $tmdb_movie['id'],
            'duration' => $tmdb_movie['runtime'],
            'tmdb' => $input['tmdb'],
            'director_id' => $tmdb_directors_id,
            'actor_id' => $tmdb_actors_id,
            'genre_id' => $tmdb_genres_id,
            'trailer_url' => $input['trailer_url'],
            'subtitle' => $input['subtitle'],
            'subtitle_list' => $input['subtitle_list'],
            'subtitle_files' => (isset($input['subtitle_files']) ? $input['subtitle_files'] : null),
            'featured' => $input['featured'],
            'series' => $input['series'],
            'detail' => $tmdb_movie['overview'],
            'rating' => $tmdb_movie['vote_average'],
            'publish_year' => $publish_year,
            'released' => $tmdb_movie['release_date'],
            'maturity_rating' => $input['maturity_rating'],
            'a_language' => $input['a_language'],
            'thumbnail' => $thumbnail,
            'poster' => $poster
          ]);


          if (isset($movie->video_link)) {
            
            if ($request->ready_url_check == 1) {

              $movie->video_link->update([
                'ready_url' => $input['ready_url'],
                'url_360' => null,
                'url_480' => null,
                'url_720' => null,
                'url_1080' => null
              ]);

            } else {

              $movie->video_link->update([
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
                'movie_id' => $movie->id,
                'ready_url' => $input['ready_url']
              ]);
              
            } else {

              VideoLink::create([
                'movie_id' => $movie->id,
                'url_360' => $input['url_360'],
                'url_480' => $input['url_480'],
                'url_720' => $input['url_720'],
                'url_1080' => $input['url_1080'],
              ]);

            }

          }

          if ($menus != null) {
            if (count($menus) > 0) {
              if (isset($movie->menus) && count($movie->menus) > 0) {
                foreach ($movie->menus as $key => $value) {
                  $value->delete();
                }
              }
              foreach ($menus as $key => $value) {
                MenuVideo::create([
                  'menu_id' => $value,
                  'movie_id' => $movie->id,
                ]);
              }
            }
          } else {
            if (isset($movie->menus) && count($movie->menus) > 0) {
              foreach ($movie->menus as $key => $value) {
                $value->delete();
              }
            }
          }

          return redirect('/admin/movies')->with('updated', 'Movie has been updated');
        }

        $director_ids = $request->input('director_id');
        if ($director_ids) {
          $director_ids = implode(',', $director_ids);
          $input['director_id'] = $director_ids;
        } else {
          $input['director_id'] = null;
        }

        $actor_ids = $request->input('actor_id');
        if ($actor_ids) {
          $actor_ids = implode(',', $actor_ids);
          $input['actor_id'] = $actor_ids;
        } else {
          $input['actor_id'] = null;
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
          if ($movie->thumbnail != null) {
            $content = @file_get_contents(public_path().'/images/movies/thumbnails/'.$movie->thumbnail);
            if ($content) { 
              unlink(public_path()."/images/movies/thumbnails/".$movie->thumbnail);
            }
          }
          $file->move('images/movies/thumbnails', $thumbnail);
          $input['thumbnail'] = $thumbnail;
        }

        if ($file = $request->file('poster')) {
          $poster = 'thumb_'.time().$file->getClientOriginalName();
          if ($movie->poster != null) {
            $content = @file_get_contents(public_path().'/images/movies/posters/'.$movie->poster);
            if ($content) { 
              unlink(public_path()."/images/movies/posters/".$movie->poster);
            }
          }
          $file->move('images/movies/posters', $poster);
          $input['poster'] = $poster;
        }

        if ($input['series'] == 1 && $movie->series == 1) {
          $movie_series = MovieSeries::where('series_movie_id', $movie->id);
          $movie_series->update([
              'movie_id' => $request->movie_id,
              'series_movie_id' => $movie->id
          ]);
        }

        if ($input['series'] == 1 && $movie->series != 1) {
          MovieSeries::create([
            'movie_id' => $request->movie_id,
            'series_movie_id' => $movie->id
          ]);
        }

        $movie->update($input);

        if (isset($movie->video_link)) {
            
          if ($request->ready_url_check == 1) {

            $movie->video_link->update([
              'ready_url' => $input['ready_url'],
              'url_360' => null,
              'url_480' => null,
              'url_720' => null,
              'url_1080' => null
            ]);

          } else {

            $movie->video_link->update([
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
              'movie_id' => $created_movie->id,
              'ready_url' => $input['ready_url']
            ]);
            
          } else {

            VideoLink::create([
              'movie_id' => $created_movie->id,
              'url_360' => $input['url_360'],
              'url_480' => $input['url_480'],
              'url_720' => $input['url_720'],
              'url_1080' => $input['url_1080'],
            ]);

          }

        }

        if ($menus != null) {
          if (count($menus) > 0) {
            if (isset($movie->menus) && count($movie->menus) > 0) {
              foreach ($movie->menus as $key => $value) {
                $value->delete();
              }
            }
            foreach ($menus as $key => $value) {
              MenuVideo::create([
                'menu_id' => $value,
                'movie_id' => $movie->id,
              ]);
            }
          }
        } else {
          if (isset($movie->menus) && count($movie->menus) > 0) {
            foreach ($movie->menus as $key => $value) {
              $value->delete();
            }
          }
        }

        return redirect('/admin/movies')->with('updated', 'Movie has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        if ($movie->thumbnail != null) {
          $content = @file_get_contents(public_path().'/images/movies/thumbnails/'.$movie->thumbnail);
          if ($content) { 
            unlink(public_path()."/images/movies/thumbnails/".$movie->thumbnail);
          }
        }
        if ($movie->poster != null) {
          $content = @file_get_contents(public_path().'/images/movies/posters/'.$movie->poster);
          if ($content) { 
            unlink(public_path()."/images/movies/posters/".$movie->poster);
          }
        }
        if ($movie->subtitle_files != null) {
          $content = @file_get_contents(public_path().'/subtitles/'.$movie->subtitle_files);
          if ($content) { 
            unlink(public_path()."/subtitles/".$movie->subtitle_files);
          }
        }

        $movie->delete();

        return back()->with('deleted', 'Movie has been deleted');
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

            $movie = Movie::findOrFail($checked);

            if ($movie->thumbnail != null) {
              $content = @file_get_contents(public_path().'/images/movies/thumbnails/'.$movie->thumbnail);
              if ($content) { 
                unlink(public_path()."/images/movies/thumbnails/".$movie->thumbnail);
              }
            }
            if ($movie->poster != null) {
              $content = @file_get_contents(public_path().'/images/movies/posters/'.$movie->poster);
              if ($content) { 
                unlink(public_path()."/images/movies/posters/".$movie->poster);
              }
            }
            if ($movie->subtitle_files != null) {
              $content = @file_get_contents(public_path().'/subtitles/'.$movie->subtitle_files);
              if ($content) { 
                unlink(public_path()."/subtitles/".$movie->subtitle_files);
              }
            }

            Movie::destroy($checked);
        }

        return back()->with('deleted', 'Movies has been deleted');   
    }

     /**
     * Translate the specified resource from storage.
     * Translate all tmdb movies on one click
     * @return \Illuminate\Http\Response
     */
    public function tmdb_translations()
    {
        ini_set('max_execution_time', 1000);
        $all_movies = Movie::where('tmdb', 'Y')->get();
        $TMDB_API_KEY = env('TMDB_API_KEY');

        if ($TMDB_API_KEY == null || $TMDB_API_KEY == '') {
          return back()->with('deleted', 'Please provide your TMDB api key to translate');
        }

        if (isset($all_movies) && count($all_movies) > 0) {
          foreach ($all_movies as $key => $movie) {
            if (Session::has('changed_language')) {
              $fetch_movie = @file_get_contents('https://api.themoviedb.org/3/movie/'.$movie->tmdb_id.'?api_key='.$TMDB_API_KEY.'&language='.Session::get('changed_language'));
            } else {
              return back()->with('updated', 'Please Choose a language by admin panel top right side language menu');
            }

            $tmdb_movie = json_decode($fetch_movie, true);
            if (isset($tmdb_movie) && $tmdb_movie != null) {
              $movie->update([
                'detail' => $tmdb_movie['overview']
              ]);
            }
          }
          return back()->with('added', 'All Movies (only by TMDB) has been translated');
        } else {
          return back()->with('updated', 'Please create at least one movie by TMDB option to translate');
        }
    } 
}
