@extends('layouts.theme')

@section('main-wrapper')
<!-- main wrapper -->
<section class="main-wrapper main-wrapper-single-movie-prime">
	<div class="background-main-poster-overlay">
	@if($movie->poster != null)
		<div class="background-main-poster col-md-offset-4 col-md-6" style="background-image: url('{{asset('images/movies/posters/'.$movie->poster)}}');">
	@else
		<div class="background-main-poster col-md-offset-4 col-md-6" style="background-image: url('{{asset('images/default-poster.jpg')}}');">
	@endif
		</div>
		<div class="overlay-bg gredient-overlay-right"></div>
		<div class="overlay-bg"></div>
	</div>

	<div id="full-movie-dtl-main-block" class="full-movie-dtl-main-block">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8">
					<div class="full-movie-dtl-block">
						<h2 class="section-heading">{{$movie->title}}</h2>
						<div class="imdb-ratings-block">
							<ul>
								<li>{{$movie->publish_year}}</li>
								<li>{{$movie->duration}} {{$popover_translations->where('key', 'mins')->first->value->value}}</li>
								<li>{{$movie->maturity_rating}}</li>
								<li>IMDB {{$movie->rating}}</li>
								@if( $movie->subtitle && $sub_titles )
								<li>CC</li>
								<li>{{ $sub_titles }}</li>
								@endif
							</ul>
						</div>

						@if ( $movie->detail )
						<p>
							{{ str_limit($movie->detail, 150, '...') }}
						</p>
						@endif
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
							<li>{!! $directors ?: '-' !!}</li>
							<li>{!! $actors ?: '-' !!}</li>
							<li>{!! $genreString ?: '-' !!}</li>
							<li>
							@if ( $movie->subtitle && $sub_titles )
								{{ $sub_titles }}
							@else
								-
							@endif
							</li>
							<li>{!! $audio_languages ?: '-' !!}</li>
						</ul>
					</div>
					<div id="wishlistelement" class="screen-play-btn-block">
						<a onclick="playVideo({{$movie->id}},'{{$movie->type}}')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
						<div class="btn-group btn-block">
							@if($movie->trailer_url != null || $movie->trailer_url != '')
							<a onclick="playTrailer('{{$movie->trailer_url}}')" class="btn btn-default">{{$popover_translations->where('key', 'watch trailer')->first->value->value}}</a>
							@endif

							<a onclick="addWish({{$movie->id}},'{{$movie->type}}')" class="addwishlistbtn{{$movie->id}}{{$movie->type}} btn-default">{{ $auth->addedWishlist($movie->id, $movie->type) ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value) }}</a>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="poster-thumbnail-block">
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
	@if ( count($movie->movie_series) > 0 && $movie->series != 1 )
	<div class="container-fluid movie-series-section search-section">
		<h5 class="movie-series-heading">Series {{count($movie->movie_series)}}</h5>
		<div>
		@foreach($movie->movie_series as $series)

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
								<a onclick="playEpisodes({{$single_series->id}})" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>

								@if($single_series->trailer_url != null || $single_series->trailer_url != '')
								<a onclick="playTrailer('{{$single_series->trailer_url}}')" class="btn-default">{{$popover_translations->where('key', 'watch trailer')->first->value->value}}</a>
								@endif

								<a onclick="addWish({{$single_series->id}},'{{$single_series->type}}')" class="addwishlistbtn{{$single_series->id}}{{$single_series->type}} btn-default">{{ $auth->addedWishlist($single_series->id, $single_series->type) ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)}}</a>
							</div>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
	@endif

	@if ( $movie->series == 1 && count($filter_series) > 0 )
	<div class="container-fluid movie-series-section search-section">
		<h5 class="movie-series-heading" style="margin-left: 45px;">{{$home_translations->where('key', 'series')->first->value->value}} {{count($filter_series)}}</h5>
		<div>
		@foreach($filter_series as $key => $series)
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

						@if ( $series->detail )
						<p>
							{{ str_limit($series->detail, 150, '...') }}
						</p>
						@endif

						<div class="des-btn-block des-in-list">
							<a onclick="playEpisodes({{$series->id}})" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>

							@if($series->trailer_url != null || $series->trailer_url != '')
							<a onclick="playTrailer('{{$series->trailer_url}}')" class="btn-default">{{$popover_translations->where('key', 'watch trailer')->first->value->value}}</a>
							@endif

							<a onclick="addWish({{$series->id}},'{{$series->type}}')" class="addwishlistbtn{{$series->id}}{{$series->type}} btn-default">{{ $auth->addedWishlist($series->id, $series->type) ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)}}</a>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
	@endif
	<!-- end movie series -->

	@if ( $auth->wishlist->count() )
	<div class="genre-prime-block">
		<div class="container-fluid">
			<h5 class="section-heading">{{$home_translations->where('key', 'customers also watched')->first->value->value}}</h5>
			<div class="genre-prime-slider owl-carousel owl-theme">
				@include ('partials.popup_slide_items', ['item_id' => $movie->id, 'item_type' => $movie->type])
			</div>
		</div>
	</div>
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