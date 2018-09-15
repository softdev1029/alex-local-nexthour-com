@extends('layouts.theme')

@section('main-wrapper')
<!-- main wrapper -->
<section id="wishlistelement" class="main-wrapper main-wrapper-single-movie-prime">
	@if(isset($filter_video))
	@if(count($filter_video) > 0)
	<div class="container-fluid movie-series-section search-section">
		<h5 class="movie-series-heading" style="margin-left: 45px;">{{count($filter_video)}} {{$home_translations->where('key', 'found for')->first->value->value}} "{{$searchKey}}"</h5>
		<div>
		@foreach($filter_video as $item)
		<div class="movie-series-block">
			<div class="row">
				<div class="col-sm-3">
					<div class="movie-series-img">
						@if($item->type == 'M' && $item->thumbnail != null)
						<img src="{{asset('images/movies/thumbnails/'.$item->thumbnail)}}" class="img-responsive" alt="genre-image">
						@elseif($item->type == 'M' && $item->thumbnail == null)  
						<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">
						@elseif($item->type == 'S')
						@if($item->thumbnail != null)
						<img src="{{asset('images/tvseries/thumbnails/'.$item->thumbnail)}}" class="img-responsive" alt="genre-image">
						@elseif($item->tvseries->thumbnail != null)
						<img src="{{asset('images/tvseries/thumbnails/'.$item->tvseries->thumbnail)}}" class="img-responsive" alt="genre-image">
						@else
						<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
						@endif
						@endif
					</div>
				</div>
				<div class="col-sm-7 pad-0">
					<h5 class="movie-series-heading movie-series-name">
						@if($item->type == 'M')
						<a href="{{url('movie/detail', $item->id)}}">{{$item->title}}</a>
						@elseif($item->type == 'S')
						<a href="{{url('show/detail', $item->id)}}">{{$item->tvseries->title}}</a>
						@endif
					</h5>
					<ul class="movie-series-des-list">
						@if($item->type == 'M')
						<li>IMDB {{$item->rating}}</li>
						@endif
						@if($item->type == 'S')
						<li>IMDB {{$item->tvseries->rating}}</li>                        
						@endif
						<li>
							@if($item->type == 'M')
							{{$item->duration}} {{$popover_translations->where('key', 'mins')->first->value->value}}
							@else
							{{$popover_translations->where('key', 'season')->first->value->value}} {{$item->season_no}}
							@endif
						</li>
						@if($item->type == 'M')
						<li>{{$item->released}}</li>
						@else
						<li>{{$item->publish_year}}</li>  
						@endif
						<li>
							@if($item->type == 'M')
							{{$item->maturity_rating}}
							@else
							{{$item->tvseries->maturity_rating}} 
							@endif
						</li>
						@if($item->subtitle == 1)
						<li>
							{{$popover_translations->where('key', 'subtitles')->first->value->value}}
						</li>
						@endif
					</ul>
					<p>
						@if($item->type == 'M')
						{{str_limit($item->detail, 360)}}
						@else
						@if ($item->detail != null || $item->detail != '')
						{{$item->detail}}
						@else  
						{{str_limit($item->tvseries->detail, 360)}}                        
						@endif
						@endif
					</p>
					<div class="des-btn-block des-in-list">
					@if($item->type == 'M')
						<a onclick="playVideo({{$item->id}},'{{$item->type}}')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>

						@if($item->trailer_url != null || $item->trailer_url != '')
						<a onclick="playTrailer('{{$item->trailer_url}}')" class="btn-default">{{$popover_translations->where('key', 'watch trailer')->first->value->value}}</a>
						@endif						
					@else
						<a onclick="playVideo({{$item->id}},'{{$item->type}}')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
					@endif

						<a onclick="addWish({{$item->id}},'{{$item->type}}')" class="addwishlistbtn{{$item->id}}{{$item->type}} btn-default">{{ $auth->addedWishlist($item->id, $item->type) ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value) }}</a>
					</div>
				</div>
			</div>
		</div>
		@endforeach
	</div>
</div>
@else
<div class="container-fluid movie-series-section search-section">
	<h5 class="movie-series-heading" style="margin-left: 45px;">0 {{$home_translations->where('key', 'found for')->first->value->value}} "{{$searchKey}}"</h5>
</div>
@endif
@endif
</section>
<!-- end main wrapper -->
<div class="video-player">
	<div class="close-btn-block text-right">
		<a class="close-btn" onclick="closeVideo()"></a>
	</div>
	<div id="my_video"></div>
</div>
@endsection
@section('custom-script')

@include ('partials.script_play')

@endsection