@extends('layouts.theme')

@section('main-wrapper')
<!-- main wrapper -->
<section class="main-wrapper">
	<div class="container-fluid">
		<div class="watchlist-section">
			<h5 class="watchlist-heading">{{$header_translations->where('key', 'watchlist')->first->value->value}}</h5>
			<div class="watchlist-btn-block">
				<div class="btn-group">
					<a href="{{url('account/watchlist/movies')}}" class="{{isset($all_movies) ? 'active' : ''}}">{{$home_translations->where('key', 'movies')->first->value->value}}</a>
					<a href="{{url('account/watchlist/shows')}}" class="{{isset($all_shows) ? 'active' : ''}}">{{$home_translations->where('key', 'tv shows')->first->value->value}}</a>
				</div>
			</div>
			@if(isset($all_shows))
			<div class="watchlist-main-block">
				@foreach($all_shows as $key => $item)
				<div class="watchlist-block">
					<div class="watchlist-img-block protip" data-pt-placement="outside" data-pt-title="#prime-show-description-block{{$item->id}}">
						<a href="{{url('show/detail',$item->id)}}">
							@if($item->thumbnail != null)
							<img src="{{asset('images/tvseries/thumbnails/'.$item->thumbnail)}}" class="img-responsive" alt="genre-image">
							@elseif($item->tvseries->thumbnail != null)
							<img src="{{asset('images/tvseries/thumbnails/'.$item->tvseries->thumbnail)}}" class="img-responsive" alt="genre-image">
							@else
							<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
							@endif
						</a>
					</div>
					{!! Form::open(['method' => 'DELETE', 'action' => ['WishListController@showdestroy', $item->id]]) !!}
					{!! Form::submit("Remove", ["class" => "remove-btn"]) !!}
					{!! Form::close() !!}
					<div id="prime-show-description-block{{$item->id}}" class="prime-description-block">
						<h5 class="description-heading">{{$item->tvseries->title}}</h5>
						<div class="movie-rating">IMDB {{$item->tvseries->rating}}</div>
						<ul class="description-list">
							<li>{{$popover_translations->where('key', 'season')->first->value->value}} {{$item->season_no}}</li>
							<li>{{$item->publish_year}}</li>
							<li>{{$item->tvseries->age_req}}</li>
							@if($item->subtitle == 1)
							<li>
								{{$popover_translations->where('key', 'subtitles')->first->value->value}}
							</li>
							@endif
						</ul>
						<div class="main-des">
							@if ($item->detail != null || $item->detail != '')
							<p>{{$item->detail}}</p>
							@else
							<p>{{$item->tvseries->detail}}</p>
							@endif
							<a href="#"></a>
						</div>
						<div class="des-btn-block">
							<a href="#" onclick="playVideo({{$item->id}}, '{{$item->type}}')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
						</div>
					</div>
				</div>
				@endforeach
			</div>
			@endif
			@if(isset($all_movies))
			<div class="watchlist-main-block">
				@foreach($all_movies as $key => $movie)
				<div class="watchlist-block">
					<div class="watchlist-img-block protip" data-pt-placement="outside" data-pt-title="#prime-description-block{{$movie->id}}">
						<a href="{{url('movie/detail',$movie->id)}}">
							@if($movie->thumbnail != null || $movie->thumbnail != '')
							<img src="{{asset('images/movies/thumbnails/'.$movie->thumbnail)}}" class="img-responsive" alt="genre-image">
							@else
							<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
							@endif
						</a>
					</div>
					{!! Form::open(['method' => 'DELETE', 'action' => ['WishListController@moviedestroy', $movie->id]]) !!}
					{!! Form::submit("Remove", ["class" => "remove-btn"]) !!}
					{!! Form::close() !!}
					<div id="prime-description-block{{$movie->id}}" class="prime-description-block">
						<div class="prime-description-under-block">                          
							<h5 class="description-heading">{{$movie->title}}</h5>
							<div class="movie-rating">IMDB {{$movie->rating}}</div>
							<ul class="description-list">
								<li>{{$movie->duration}} {{$popover_translations->where('key', 'mins')->first->value->value}}</li>
								<li>{{$movie->publish_year}}</li>
								<li>{{$movie->maturity_rating}}</li>
								@if($movie->subtitle == 1)
								<li>
									{{$popover_translations->where('key', 'subtitles')->first->value->value}}
								</li>
								@endif
							</ul>
							<div class="main-des">
								<p>{{$movie->detail}}</p>
								<a href="#"></a>
							</div>
							<div class="des-btn-block">
								<a href="#" onclick="playVideo({{$movie->id}}, '{{$movie->type}}')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
								@if($movie->trailer_url != null || $movie->trailer_url != '')
								<a href="#" onclick="playTrailer('{{$movie->trailer_url}}')" class="btn-default">{{$popover_translations->where('key', 'watch trailer')->first->value->value}}</a>
								@endif
							</div>
						</div>  
					</div>
				</div>
				@endforeach
			</div>
			@endif
		</div>
	</div>
</section>
<div class="video-player">
	<div class="close-btn-block text-right">
		<a class="close-btn" onclick="closeVideo()"></a>
	</div>
	<div id="my_video"></div>
</div>
<!-- end main wrapper -->
@endsection

@section('custom-script')

@include ('partials.script_play')

@endsection