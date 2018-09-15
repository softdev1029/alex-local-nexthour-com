@extends('layouts.theme')

@section('main-wrapper')
<!-- main wrapper -->
<section class="main-wrapper">
	@if($movie->poster != null)
	<div id="big-main-poster-block" class="big-main-poster-block" style="background-image: url('{{asset('images/movies/posters/'.$movie->poster)}}');">
		<div class="overlay-bg"></div>
	</div>
	@else
	<div id="big-main-poster-block" class="big-main-poster-block" style="background-image: url('{{asset('images/default-poster.jpg')}}');">
		<div class="overlay-bg"></div>
	</div>
	@endif 
	
	<div id="full-movie-dtl-main-block" class="full-movie-dtl-main-block full-movie-dtl-block-custom">
		<div class="container-fluid">
		@php
			$subtitles = collect();
			if ($movie->subtitle == 1) {
				$subtitle_list = explode(',', $movie->subtitle_list);
				for($i = 0; $i < count($subtitle_list); $i++) {
					try {
						$subtitle = \App\AudioLanguage::find($subtitle_list[$i])->language;
						$subtitles->push($subtitle);
					} catch (Exception $e) {
					}
				}
			}

			$a_languages = collect();
			if ($movie->a_language != null) {
				$a_lan_list = explode(',', $movie->a_language);
				for($i = 0; $i < count($a_lan_list); $i++) {
					try {
						$a_language = \App\AudioLanguage::find($a_lan_list[$i])->language;
						$a_languages->push($a_language);
					} catch (Exception $e) {
					}
				}
			}

			$wishlist_check = \Illuminate\Support\Facades\DB::table('wishlists')->where([
				['user_id', '=', $auth->id],
				['movie_id', '=', $movie->id],
			])->first();
			
			// Directors list of movie from model
			$directors = collect();
			if ($movie->director_id != null) {
				$p_directors_list = explode(',', $movie->director_id);
				for($i = 0; $i < count($p_directors_list); $i++) {
					try {
						$p_director = \App\Director::find($p_directors_list[$i])->name;
						$directors->push($p_director);
					} catch (Exception $e) {
					}
				}
			}

			// Actors list of movie from model
			$actors = collect();
			if ($movie->actor_id != null) {
				$p_actors_list = explode(',', $movie->actor_id);
				for($i = 0; $i < count($p_actors_list); $i++) {
					try {
						$p_actor = \App\Actor::find($p_actors_list[$i])->name;
						$actors->push($p_actor);
					} catch (Exception $e) {
					}
				}
			}

			// Genre list of movie from model
			$genres = collect();
			if (isset($movie->genre_id)){
				$genre_list = explode(',', $movie->genre_id);
				for ($i = 0; $i < count($genre_list); $i++) {
					try {
						$genre = \App\Genre::find($genre_list[$i])->name;
						$genres->push($genre);
					} catch (Exception $e) {
					}
				}
			}
		@endphp

			<div class="row">
				<div class="col-md-8">
					<div class="full-movie-dtl-block">
						<h2 id="full-movie-name" class="section-heading">{{$movie->title}}</h2>
						<div class="imdb-ratings-block">
							<ul>
								<li>{{$movie->publish_year}}</li>
								<li>{{$movie->duration}} {{$popover_translations->where('key', 'mins')->first->value->value}}</li>
								<li>{{$movie->maturity_rating}}</li>
								<li>IMDB {{$movie->rating}}</li>
								@if($movie->subtitle == 1 && isset($subtitles))
								<li>CC</li>
								<li>
									@for($i = 0; $i < count($subtitles); $i++)
									@if($i == count($subtitles)-1)
									{{$subtitles[$i]}}
									@else
									{{$subtitles[$i]}},
									@endif
									@endfor
								</li>
								@endif
							</ul>
						</div>
						<p>
							{{$movie->detail}}
						</p>
					</div>
					<div class="screen-casting-dtl">
						<ul class="casting-headers">
							<li>{{$home_translations->where('key', 'directors')->first->value->value}}</li>
							<li>{{$home_translations->where('key', 'starring')->first->value->value}}</li>
							<li>{{$home_translations->where('key', 'genres')->first->value->value}}</li>
							<li>{{$popover_translations->where('key', 'subtitles')->first->value->value}}</li>
							<li>{{$home_translations->where('key', 'audio languages')->first->value->value}}</li>
						</ul>
						<ul class="casting-dtl">
							<li>
								@if (count($directors) > 0)
								@for($i = 0; $i < count($directors); $i++)
								@if($i == count($directors)-1)
								<a href="{{url('video/detail/director_search', trim($directors[$i]))}}">{{$directors[$i]}}</a>
								@else
								<a href="{{url('video/detail/director_search', trim($directors[$i]))}}">{{$directors[$i]}}</a>,
								@endif
								@endfor
								@else
								-  
								@endif
							</li>
							<li>
								@if (count($actors) > 0)
								@for($i = 0; $i < count($actors); $i++)
								@if($i == count($actors)-1)
								<a href="{{url('video/detail/actor_search', trim($actors[$i]))}}">{{$actors[$i]}}</a>
								@else
								<a href="{{url('video/detail/actor_search', trim($actors[$i]))}}">{{$actors[$i]}}</a>,
								@endif
								@endfor
								@else
								-  
								@endif
							</li>
							<li>
								@if (count($genres) > 0)
								@for($i = 0; $i < count($genres); $i++)
								@if($i == count($genres)-1)
								<a href="{{url('video/detail/genre_search', trim($genres[$i]))}}">{{$genres[$i]}}</a>
								@else
								<a href="{{url('video/detail/genre_search', trim($genres[$i]))}}">{{$genres[$i]}}</a>,
								@endif
								@endfor
								@else
								-
								@endif
							</li>
							<li>
								@if (count($subtitles) > 0)
								@if($movie->subtitle == 1 && isset($subtitles))
								@for($i = 0; $i < count($subtitles); $i++)
								@if($i == count($subtitles)-1)
								{{$subtitles[$i]}}
								@else
								{{$subtitles[$i]}},
								@endif
								@endfor
								@else
								-
								@endif
								@else
								-
								@endif
							</li>
							<li>
								@if (count($a_languages) > 0)
								@if($movie->a_language != null && isset($a_languages))
								@for($i = 0; $i < count($a_languages); $i++)
								@if($i == count($a_languages)-1)
								{{$a_languages[$i]}}
								@else
								{{$a_languages[$i]}},
								@endif
								@endfor
								@else
								-
								@endif
								@else
								-
								@endif
							</li>
						</ul>
					</div>
					<div id="wishlistelement" class="screen-play-btn-block">
						<a onclick="playVideo({{$movie->id}},'{{$movie->type}}')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
						<div class="btn-group btn-block">
							@if ( isset($_COOKIE['jwplayer#' . $movie->id]) && $_COOKIE['jwplayer#' . $movie->id] )
							<a onclick="playVideo({{$movie->id}}, '{{$movie->type}}')" class="btn btn-default">{{$popover_translations->where('key', 'continue watching')->first->value->value}}</a>
							@endif

							@if($movie->trailer_url != null || $movie->trailer_url != '')
							<a onclick="playTrailer('{{$movie->trailer_url}}')" class="btn btn-default">{{$popover_translations->where('key', 'watch trailer')->first->value->value}}</a>
							@endif
							@if (isset($wishlist_check->added))
							<a onclick="addWish({{$movie->id}},'{{$movie->type}}')" class="addwishlistbtn{{$movie->id}}{{$movie->type}} btn-default">{{$wishlist_check->added == 1 ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)}}</a>
							@else
							<a onclick="addWish({{$movie->id}},'{{$movie->type}}')" class="addwishlistbtn{{$movie->id}}{{$movie->type}} btn-default">{{$popover_translations->where('key', 'add to watchlist')->first->value->value}}</a>
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div id="poster-thumbnail" class="poster-thumbnail-block">
						@if($movie->thumbnail != null || $movie->thumbnail != '')
						<img src="{{asset('images/movies/thumbnails/'.$movie->thumbnail)}}" class="img-responsive" alt="genre-image">
						@else
						<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- movie series -->
	@if(count($movie->movie_series) && $movie->series != 1)
	<div class="container-fluid movie-series-section search-section">
		<h5 class="movie-series-heading">Series {{count($movie->movie_series)}}</h5>
		<div>
		@foreach($movie->movie_series as $series)
			@php
			$single_series = \App\Movie::where('id', $series->series_movie_id)->first();
			$wishlist_check = \Illuminate\Support\Facades\DB::table('wishlists')->where([
				['user_id', '=', $auth->id],
				['movie_id', '=', $single_series->id],
			])->first();
			@endphp
			<div class="movie-series-block">
				<div class="row">
					<div class="col-sm-3">
						<div class="movie-series-img">
							@if($single_series->thumbnail != null || $single_series->thumbnail != '')
							<img src="{{asset('images/movies/thumbnails/'.$single_series->thumbnail)}}" class="img-responsive" alt="genre-image">
							@endif
						</div>
					</div>
					<div class="col-sm-7 pad-0">
						<h5 class="movie-series-heading movie-series-name"><a href="{{url('movie/detail', $single_series->id)}}">{{$single_series->title}}</h5>
						<ul class="movie-series-des-list">
							<li>IMDB {{$single_series->rating}}</li>
							<li>{{$single_series->duration}} {{$popover_translations->where('key', 'mins')->first->value->value}}</li>
							<li>{{$single_series->publish_year}}</li>
							<li>{{$single_series->maturity_rating}}</li>
							@if($single_series->subtitle == 1)
							<li>{{$popover_translations->where('key', 'subtitles')->first->value->value}}</li>
							@endif
						</ul>
						<p>
							{{$single_series->detail}}
						</p>
						<div class="des-btn-block des-in-list">
							<a onclick="playVideo({{$single_series->id}}, '{{$single_series->type}}')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
							@if($single_series->trailer_url != null || $single_series->trailer_url != '')

							@if ( isset($_COOKIE['jwplayer#' . $item->id]) && $_COOKIE['jwplayer#' . $item->id] )
							<a onclick="playVideo({{$item->id}},'{{$item->type}}')" class="btn btn-default">{{$popover_translations->where('key', 'continue watching')->first->value->value}}</a>
							@endif

							<a onclick="playTrailer('{{$single_series->trailer_url}}')" class="btn-default">{{$popover_translations->where('key', 'watch trailer')->first->value->value}}</a>
							@endif
							@if (isset($wishlist_check->added))
							<a onclick="addWish({{$single_series->id}},'{{$single_series->type}}')" class="addwishlistbtn{{$single_series->id}}{{$single_series->type}} btn-default">{{$wishlist_check->added == 1 ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)}}</a>
							@else
							<a onclick="addWish({{$single_series->id}},'{{$single_series->type}}')" class="addwishlistbtn{{$single_series->id}}{{$single_series->type}} btn-default">{{$popover_translations->where('key', 'add to watchlist')->first->value->value}}</a>
							@endif
						</div>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	</div>
	@endif

	@if(count($filter_series) && $movie->series == 1)
	<div class="container-fluid movie-series-section search-section">
		<h5 class="movie-series-heading">{{$home_translations->where('key', 'series')->first->value->value}} {{count($filter_series)}}</h5>
		<div>
			@foreach($filter_series as $key => $series)
			@php
			$wishlist_check = \Illuminate\Support\Facades\DB::table('wishlists')->where([
				['user_id', '=', $auth->id],
				['movie_id', '=', $series->id],
			])->first();
			@endphp
			<div class="movie-series-block">
				<div class="row">
					<div class="col-sm-3">
						<div class="movie-series-img">
							@if($series->thumbnail != null)
							<img src="{{asset('images/movies/thumbnails/'.$series->thumbnail)}}" class="img-responsive" alt="genre-image">
							@endif
						</div>
					</div>
					<div class="col-sm-7 pad-0">
						<h5 class="movie-series-heading movie-series-name"><a href="{{url('movie/detail', $series->id)}}">{{$series->title}}</a></h5>
						<ul class="movie-series-des-list">
							<li>IMDB {{$series->rating}}</li>
							<li>{{$series->duration}} {{$popover_translations->where('key', 'mins')->first->value->value}}</li>
							<li>{{$series->publish_year}}</li>
							<li>{{$series->maturity_rating}}</li>
							@if($series->subtitle == 1)
							<li>{{$popover_translations->where('key', 'subtitles')->first->value->value}}</li>
							@endif
						</ul>
						<p>
							{{$series->detail}}
						</p>
						<div class="des-btn-block des-in-list">
							<a onclick="playVideo({{$series->id}}, '{{$series->type}}')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>

							@if ( isset($_COOKIE['jwplayer#S' . $series->id]) && $_COOKIE['jwplayer#S' . $series->id] )
							<a onclick="playEpisodes({{$series->id}})" class="btn-default">{{$popover_translations->where('key', 'continue watching')->first->value->value}}</a>
							@endif

							@if($series->trailer_url != null || $series->trailer_url != '')
							<a onclick="playTrailer('{{$series->trailer_url}}')" class="btn-default">{{$popover_translations->where('key', 'watch trailer')->first->value->value}}</a>
							@endif
							@if (isset($wishlist_check->added))
							<a onclick="addWish({{$series->id}},'{{$series->type}}')" class="addwishlistbtn{{$series->id}}{{$series->type}} btn-default">{{$wishlist_check->added == 1 ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)}}</a>
							@else
							<a onclick="addWish({{$series->id}},'{{$series->type}}')" class="addwishlistbtn{{$series->id}}{{$series->type}} btn-default">{{$popover_translations->where('key', 'add to watchlist')->first->value->value}}</a>
							@endif
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
	@endif
	<!-- end movie series -->
	
	@if($prime_genre_slider == 1)
		@php
			$all = collect();
			$all_fil_movies = App\Movie::all();
			$all_fil_tv = App\TvSeries::all();
			$genres = explode(',', $movie->genre_id);
			for($i = 0; $i < count($genres); $i++) {
				foreach ($all_fil_movies as $fil_movie) {
					$fil_genre_item = explode(',', trim($fil_movie->genre_id));
					for ($k=0; $k < count($fil_genre_item); $k++) { 
						if (trim($fil_genre_item[$k]) == trim($genres[$i])) {
							if ($fil_movie->id != $movie->id) {
								$all->push($fil_movie);
							}
						}
					}
				}
			}

			$all = $all->except($movie->id);

			for($i = 0; $i < count($genres); $i++) {
				foreach ($all_fil_tv as $fil_tv) {
					$fil_genre_item = explode(',', trim($fil_tv->genre_id));
					for ($k=0; $k < count($fil_genre_item); $k++) { 
						if (trim($fil_genre_item[$k]) == trim($genres[$i])) {
							$fil_tv = $fil_tv->seasons;
							$all->push($fil_tv);
						}
					}
				}
			}
			$all = $all->unique();
			$all = $all->flatten();
		@endphp

		@if (isset($all) && count($all) > 0)
		<div class="genre-prime-block">
			<div class="container-fluid">
				<h5 class="section-heading">{{$home_translations->where('key', 'customers also watched')->first->value->value}}</h5>
				<div class="genre-prime-slider owl-carousel">
					@include ('partials.popup_slide_items', ['items' => $all])
				</div>
			</div>
		</div>
		@endif
	@else
		@php
			$all = collect();
			$all_fil_movies = App\Movie::all();
			$all_fil_tv = App\TvSeries::all();
			$genres = explode(',', $movie->genre_id);
			
			for($i = 0; $i < count($genres); $i++) {
				foreach ($all_fil_movies as $fil_movie) {
					$fil_genre_item = explode(',', trim($fil_movie->genre_id));
						for ($k=0; $k < count($fil_genre_item); $k++) { 
						if (trim($fil_genre_item[$k]) == trim($genres[$i])) {
							if ($fil_movie->id != $movie->id) {
								$all->push($fil_movie);
							}
						}
					}
				}
			}

			$all = $all->except($movie->id);

			for($i = 0; $i < count($genres); $i++) {
				foreach ($all_fil_tv as $fil_tv) {
					$fil_genre_item = explode(',', trim($fil_tv->genre_id));
					for ($k=0; $k < count($fil_genre_item); $k++) { 
						if (trim($fil_genre_item[$k]) == trim($genres[$i])) {
							$fil_tv = $fil_tv->seasons;
							$all->push($fil_tv);
						}
					}
				}
			}

			$all = $all->unique();
			$all = $all->flatten();
		@endphp

		@if (isset($all) && count($all) > 0)
		<div class="genre-main-block">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3">
						<div class="genre-dtl-block">
							<h3 class="section-heading">{{$home_translations->where('key', 'customers also watched')->first->value->value}}</h3>
							<p class="section-dtl">{{$home_translations->where('key', 'at the big screen at home')->first->value->value}}</p>
						</div>
					</div>
					<div class="col-md-9">
						<div class="genre-main-slider owl-carousel">
							@include ('partials.slide_items', ['items' => $all])
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	@endif
</section>
<!-- end main wrapper -->

<!-- Player -->
<div class="video-player">
	<div class="close-btn-block text-right">
		<a class="close-btn" onclick="closeVideo()"></a>
	</div>
	<div id="my_video"></div>
</div>
<!-- End Player -->
@endsection

@section('custom-script')

@include ('partials.script_play')

@endsection