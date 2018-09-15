@extends('layouts.theme')

@section('main-wrapper')
<!-- main wrapper -->
<section class="main-wrapper">
	@if($season->poster != null)
	<div id="big-main-poster-block" class="big-main-poster-block" style="background-image: url('{{asset('images/tvseries/posters/'.$season->poster)}}');">
		<div class="overlay-bg"></div>
	</div>
	@elseif($season->tvseries->poster != null)
	<div id="big-main-poster-block" class="big-main-poster-block" style="background-image: url('{{asset('images/tvseries/posters/'.$season->tvseries->poster)}}');">
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
			if ($season->subtitle == 1) {
				$subtitle_list = explode(',', $season->subtitle_list);
				for($i = 0; $i < count($subtitle_list); $i++) {
					try {
						$subtitle = \App\AudioLanguage::find($subtitle_list[$i])->language;
						$subtitles->push($subtitle);
					} catch (Exception $e) {
					}
				}
			}
				
			$a_languages = collect();
			if ($season->a_language != null) {
				$a_lan_list = explode(',', $season->a_language);
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
				['season_id', '=', $season->id],
			])->first();

			// Actors list of movie from model
			$actors = collect();
			if ($season->actor_id != null) {
				$p_actors_list = explode(',', $season->actor_id);
				for($i = 0; $i < count($p_actors_list); $i++) {
					try {
						$p_actor = \App\Actor::find(trim($p_actors_list[$i]))->name;
						$actors->push($p_actor);
					} catch (Exception $e) {
					}
				}
			}

			// Genre list of movie from model
			$genres = collect();
			if ($season->tvseries->genre_id != null){
				$genre_list = explode(',', $season->tvseries->genre_id);
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
						<h2 id="full-movie-name" class="section-heading">{{$season->tvseries->title}}</h2>
						<div class="imdb-ratings-block">
							<ul>
								<li>{{$season->publish_year}}</li>
								<li>{{$season->season_no}} {{$popover_translations->where('key', 'season')->first->value->value}}</li>
								<li>{{$season->tvseries->age_req}}</li>
								<li>IMDB {{$season->tvseries->rating}}</li>
								@if(isset($subtitles))
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
							@if ($season->detail != null || $season->detail != '')
							{{$season->detail}}
							@else
							{{$season->tvseries->detail}}  
							@endif
						</p>
					</div>
					<div class="screen-casting-dtl">
						<ul class="casting-headers">
							<li>{{$home_translations->where('key', 'starring')->first->value->value}}</li>
							<li>{{$home_translations->where('key', 'genres')->first->value->value}}</li>
							<li>{{$popover_translations->where('key', 'subtitles')->first->value->value}}</li>
							<li>{{$home_translations->where('key', 'audio languages')->first->value->value}}</li>
						</ul>
						<ul class="casting-dtl">
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
							</li>
							<li>
								@if($season->a_language != null && isset($a_languages))
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
							</li>
						</ul>
					</div>
					<div class="screen-play-btn-block">
						<a onclick="playEpisodes({{$season->id}})" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
						<div id="wishlistelement" class="btn-group btn-block">
							<div>
								@if (isset($wishlist_check->added))
								<a onclick="addWish({{$season->id}},'{{$season->type}}')" class="addwishlistbtn{{$season->id}}{{$season->type}} btn-default">{{$wishlist_check->added == 1 ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)}}</a>
								@else
								<a onclick="addWish({{$season->id}},'{{$season->type}}')" class="addwishlistbtn{{$season->id}}{{$season->type}} btn-default">{{$popover_translations->where('key', 'add to watchlist')->first->value->value}}</a>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div id="poster-thumbnail" class="poster-thumbnail-block">
						@if($season->thumbnail != null)
						<img src="{{asset('images/tvseries/thumbnails/'.$season->thumbnail)}}" class="img-responsive" alt="genre-image">
						@elseif($season->tvseries->thumbnail != null)
						<img src="{{asset('images/tvseries/thumbnails/'.$season->tvseries->thumbnail)}}" class="img-responsive" alt="genre-image">
						@else
						<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	@if(count($season->episodes) > 0)
	<div class="container-fluid movie-series-section search-section">
		<h5 class="movie-series-heading">{{$home_translations->where('key', 'episodes')->first->value->value}} {{count($season->episodes)}}</h5>
		<div>
			@foreach($season->episodes as $key => $episode)
			<div class="movie-series-block">
				<div class="row">
					<div class="col-sm-3">
						<div class="movie-series-img">
							@if($episode->seasons->thumbnail != null)
							<img src="{{asset('images/tvseries/thumbnails/'.$episode->seasons->thumbnail)}}" class="img-responsive" alt="genre-image">
							@elseif($episode->seasons->tvseries->thumbnail != null)
							<img src="{{asset('images/tvseries/thumbnails/'.$episode->seasons->tvseries->thumbnail)}}" class="img-responsive" alt="genre-image">
							@else
							<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
							@endif
						</div>
					</div>
					<div class="col-sm-7 pad-0">
						<a onclick="playEpisodes({{$episode->seasons_id}}, {{$key}})" class="btn btn-play btn-sm-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><h5 class="movie-series-heading movie-series-name">{{$key+1}}. {{$episode->title}}</h5></span></a>
						<ul class="movie-series-des-list">
							<li>{{$episode->duration}} {{$popover_translations->where('key', 'mins')->first->value->value}}</li>
							<li>{{$episode->released}}</li>
							<li>{{$episode->seasons->tvseries->maturity_rating}}</li>
							<li>
								@if($episode->seasons->subtitle == 1)
								{{$popover_translations->where('key', 'subtitles')->first->value->value}}
								@endif
							</li>
						</ul>
						<p>
							{{$episode->detail}}
						</p>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
	@endif
	<!-- end episodes -->

	@if($prime_genre_slider == 1)
		@php
			$all = collect();
			$all_fil_movies = App\Movie::all();
			$all_fil_tv = App\TvSeries::all();
			$genres = explode(',', $season->tvseries->genre_id);
			for($i = 0; $i < count($genres); $i++) {
				foreach ($all_fil_movies as $fil_movie) {
					$fil_genre_item = explode(',', trim($fil_movie->genre_id));
					for ($k=0; $k < count($fil_genre_item); $k++) { 
						if (trim($fil_genre_item[$k]) == trim($genres[$i])) {
							$all->push($fil_movie);
						}
					}
				}
			}

			for($i = 0; $i < count($genres); $i++) {
				foreach ($all_fil_tv as $fil_tv) {
					$fil_genre_item = explode(',', trim($fil_tv->genre_id));
					for ($k=0; $k < count($fil_genre_item); $k++) { 
						if (trim($fil_genre_item[$k]) == trim($genres[$i])) {
							$fil_tv = $fil_tv->seasons;
							$all->push($fil_tv->except($season->id));
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
		$genres = explode(',', $season->tvseries->genre_id);

		for($i = 0; $i < count($genres); $i++) {
			foreach ($all_fil_movies as $fil_movie) {
				$fil_genre_item = explode(',', trim($fil_movie->genre_id));
				for ($k=0; $k < count($fil_genre_item); $k++) { 
					if (trim($fil_genre_item[$k]) == trim($genres[$i])) {
						$all->push($fil_movie);
					}
				}
			}
		}

		for($i = 0; $i < count($genres); $i++) {
			foreach ($all_fil_tv as $fil_tv) {
				$fil_genre_item = explode(',', trim($fil_tv->genre_id));
				for ($k=0; $k < count($fil_genre_item); $k++) { 
					if (trim($fil_genre_item[$k]) == trim($genres[$i])) {
						$fil_tv = $fil_tv->seasons;
						$all->push($fil_tv->except($season->id));
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