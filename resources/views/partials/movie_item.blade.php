<div class="genre-prime-slide">
	<div class="genre-slide-image protip" data-pt-placement="outside" data-pt-title="#section_{{$section}}{{$item->id}}{{$item->type}}">
		<a href="{{url('movie/detail',$item->id)}}">
			@if($item->thumbnail != null)
			<img src="{{asset('images/movies/thumbnails/'.$item->thumbnail)}}" class="img-responsive" alt="genre-image">
			@endif
		</a>
	</div>
	<div id="section_{{$section}}{{$item->id}}{{$item->type}}" class="prime-description-block">
		<h5 class="description-heading">{{$item->title}}</h5>
		<div class="movie-rating">IMDB {{$item->rating}}</div>
		<ul class="description-list">
			<li>{{$item->duration}} {{$popover_translations->where('key', 'mins')->first->value->value}}</li>
			<li>{{$item->publish_year}}</li>
			<li>{{$item->maturity_rating}}</li>
			@if($item->subtitle == 1)
			<li>CC</li>
			<li>
				{{$popover_translations->where('key', 'subtitles')->first->value->value}}
			</li>
			@endif
		</ul>

		@if ( $item->detail )
		<div class="main-des">
			<p>{{ str_limit($item->detail, 150, '...') }}</p>
		</div>
		@endif

		<div class="des-btn-block">
			<a onclick="playVideo({{$item->id}},'{{$item->type}}')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
			
			@if($item->trailer_url != null || $item->trailer_url != '')
			<a onclick="playTrailer('{{$item->trailer_url}}')" class="btn-default">{{$popover_translations->where('key', 'watch trailer')->first->value->value}}</a>
			@endif

			<a onclick="addWish({{$item->id}},'{{$item->type}}')" class="addwishlistbtn{{$item->id}}{{$item->type}} btn-default">{{ $auth->addedWishlist($item->id, $item->type) ? $popover_translations->where('key', 'remove from watchlist')->first->value->value : $popover_translations->where('key', 'add to watchlist')->first->value->value}}</a>
		</div>
	</div>
</div>