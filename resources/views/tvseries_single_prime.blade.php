@extends('layouts.theme')

@section('main-wrapper')
<!-- main wrapper -->
<section class="main-wrapper main-wrapper-single-movie-prime">
	<div class="background-main-poster-overlay">
	@if($tvseries->poster != null)
		<div class="background-main-poster col-md-offset-4 col-md-6" style="background-image: url('{{asset('images/tvseries/posters/'.$tvseries->poster)}}');">
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
						<h2 class="section-heading">{{$tvseries->title}}</h2>
						<div class="imdb-ratings-block">
							<ul>
								<li>{{$tvseries->maturity_rating }}</li>
								<li>IMDB {{ $tvseries->rating }}</li>
								@if ( isset($meta['sub_titles']) )
								<li>CC</li>
								@endif
							</ul>
						</div>
						<p>
							{{$tvseries->detail}}
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
							<li>{!! $genreString ? $genreString : '-' !!}</li>
							<li>{!! isset($meta['actors']) ? implode(', ', $meta['actors']) : '-' !!}</li>
							<li>{!! isset($meta['sub_titles']) ? implode(', ', $meta['sub_titles']) : '-' !!}</li>
							<li>{!! isset($meta['audio_languages']) ? implode(', ', $meta['audio_languages']) : '-' !!}</li>
						</ul>
					</div>
					<div class="screen-play-btn-block">
						<a onclick="playEpisodes({{$tvseries->id}})" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
						<div id="wishlistelement" class="btn-group btn-block">
							<a onclick="addWish({{$tvseries->id}},'{{$tvseries->type}}')" class="addwishlistbtn{{$tvseries->id}}{{$tvseries->type}} btn-default">{{ $auth->addedWishlist($tvseries->id, $tvseries->type) ? $popover_translations->where('key', 'remove from watchlist')->first->value->value : $popover_translations->where('key', 'add to watchlist')->first->value->value}}</a>
						</div>
					</div>		
				</div>
				<div class="col-md-4">
					<div class="poster-thumbnail-block">
						@if($tvseries->thumbnail != null)
						<img src="{{asset('images/tvseries/thumbnails/'.$tvseries->thumbnail)}}" class="img-responsive" alt="genre-image">
						@else
						<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- episodes -->
	@if(count($tvseries->seasons) > 0)
	<div class="container-fluid movie-series-section search-section">
		<h5 class="movie-series-heading" style="margin-left: 45px;">{{$home_translations->where('key', 'seasons')->first->value->value}} {{count($tvseries->seasons)}}</h5>
		<div>
			@foreach($tvseries->seasons as $key => $season)
			<div class="movie-series-block">
				<div class="row">
					<div class="col-sm-3">
						<div class="movie-series-img">
							@if($season->thumbnail != null)
							<img src="{{asset('images/tvseries/thumbnails/'.$season->thumbnail)}}" class="img-responsive" alt="genre-image">
							@elseif($tvseries->thumbnail != null)
							<img src="{{asset('images/tvseries/thumbnails/'.$tvseries->thumbnail)}}" class="img-responsive" alt="genre-image">
							@else
							<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
							@endif
						</div>
					</div>
					<div class="col-sm-7 pad-0">
						<a onclick="playEpisodes({{$season->id}}, {{$key}})" class="btn btn-play btn-sm-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><h5 class="movie-series-heading movie-series-name">{{$key+1}}. {{$season->detail}}</h5></span></a>
						<ul class="movie-series-des-list">
							<li>{{ $season->publish_year }}</li>
							<li>{!! $season->subTitleString() !!}</li>
						</ul>
						<ul class="movie-series-des-list">
							<li>{!! $season->actorString() !!}</li>
						</ul>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
	@endif
	<!-- end episodes -->

	<!-- Continue watching -->
	@if ( count($auth->continueWatchings()) )
	<div class="genre-prime-block">
		<div class="container-fluid">
			<h5 class="section-heading">{{$popover_translations->where('key', 'continue watching')->first->value->value}}</h5>
			<div class="genre-prime-slider owl-carousel">
				@include ('partials.continue_watchings')
			</div>
		</div>
	</div>	
	@endif

	@if ( $auth->wishlist->count() )
	<div class="genre-prime-block">
		<div class="container-fluid">
			<h5 class="section-heading">{{$home_translations->where('key', 'customers also watched')->first->value->value}}</h5>
			<div class="genre-prime-slider owl-carousel">
				@include ('partials.popup_slide_items', ['item_id' => $tvseries->id, 'item_type' => $tvseries->type])
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