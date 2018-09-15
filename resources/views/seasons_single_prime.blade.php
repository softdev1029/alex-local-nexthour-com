@extends('layouts.theme')

@section('main-wrapper')
<!-- main wrapper -->
<section class="main-wrapper main-wrapper-single-movie-prime">
	<div class="background-main-poster-overlay">
	@if($season->poster != null)
		<div class="background-main-poster col-md-offset-4 col-md-6" style="background-image: url('{{asset('images/tvseries/posters/'.$season->poster)}}');">
	@elseif($season->tvseries->poster != null)
		<div class="background-main-poster col-md-offset-4 col-md-6" style="background-image: url('{{asset('images/tvseries/posters/'.$season->tvseries->poster)}}');">
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
						<h2 class="section-heading">{{ $season->tvseries->title }}</h2>
						<div class="imdb-ratings-block">
							<ul>
								<li>{{ $season->publish_year }}</li>
								<li>{{ $season->season_no }} {{ $popover_translations->where('key', 'season')->first->value->value }}</li>
								<li>{{ $season->tvseries->age_req }}</li>
								<li>IMDB {{ $season->tvseries->rating }}</li>
								@if ( $season->subtitle && $sub_titles )
								<li>CC</li>
								<li>{{ $sub_titles }}</li>
								@endif
							</ul>
						</div>
						<p>
							@if ( $season->detail )
								{{ str_limit($season->detail, 150, '...') }}
							@else
								{{ str_limit($season->tvseries->detail, 150, '...') }}
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
							<li>{!! $actors ?: '-' !!}</li>
							<li>{!! $genreString ?: '-' !!}</li>
							<li>
							@if ( $season->subtitle && $sub_titles )
								{{ $sub_titles }}
							@else
								-
							@endif
							</li>
							<li>{!! $audio_languages ?: '-' !!}</li>
						</ul>
					</div>
					<div class="screen-play-btn-block">
						<a onclick="playEpisodes({{$season->id}})" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
						<div id="wishlistelement" class="btn-group btn-block">
							<div>
								<a onclick="addWish({{$season->id}},'{{$season->type}}')" class="addwishlistbtn{{$season->id}}{{$season->type}} btn-default">{{ $auth->addedWishlist($season->id, $season->type) ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value) }}</a>
							</div>
						</div>
					</div>		
				</div>
				<div class="col-md-4">
					<div class="poster-thumbnail-block">
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

	@if ( count($season->tvseries->seasons) > 1 )
	<div class="container-fluid movie-series-section search-section" style="z-index:10; margin-bottom:0 !important;">
		<div class="row">
			<div class="col-sm-3">
				<h5 class="movie-series-heading" style="padding:5px 0 0;">{{ $home_translations->where('key', 'seasons')->first->value->value }} {{ count($season->tvseries->seasons) }}</h5>
			</div>
			<div class="col-sm-9">
				<div class="pull-left" style="margin:10px 0 0;">
					<select id="selectSeason" class="selectpicker" data-style="btn-default">
					@foreach($season->tvseries->seasons as $key => $ss)
						<option value="{{ $ss->id }}"{{ $season->id == $ss->id ? ' selected' : '' }}>{{ $ss->detail ? $ss->detail : $popover_translations->where('key', 'season')->first->value->value . ' ' . ($key + 1) }}</option>
					@endforeach
					</select>
				</div>
			</div>
		</div>
	</div>
	@endif

	<!-- episodes -->
	@if(count($season->episodes) > 0)
	<div class="container-fluid movie-series-section search-section" style="padding-top:0; ">
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
						@if ( $episode->detail )
						<p>
							{{ str_limit($episode->detail, 150, '...') }}
						</p>
						@endif
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
	@endif
	<!-- end episodes -->

	@if ( $auth->wishlist->count() )
	<div class="genre-prime-block">
		<div class="container-fluid">
			<h5 class="section-heading">{{$home_translations->where('key', 'customers also watched')->first->value->value}}</h5>
			<div class="genre-prime-slider owl-carousel owl-theme">
				@include ('partials.popup_slide_items', ['item_id' => $season->id, 'item_type' => $season->type])
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

<script type="text/javascript">
$(document).ready(function() {
	$('#selectSeason').on('change', function() {
		location.href = '{{ url("show/detail/") }}/' + $(this).val();
	});
});
</script>

@include ('partials.script_play')

@endsection