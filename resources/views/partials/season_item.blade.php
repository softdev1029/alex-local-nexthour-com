<div class="genre-prime-slide item">
	<div class="genre-slide-image protip" data-pt-placement="outside" data-pt-title="#section_{{$section}}{{$item->id}}{{$item->type}}">
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
	<div id="section_{{$section}}{{$item->id}}{{$item->type}}" class="prime-description-block">
		<h5 class="description-heading">{{$item->tvseries->title}}</h5>
		<div class="movie-rating">IMDB {{$item->tvseries->rating}}</div>
		<ul class="description-list">
			<li>Season {{$item->season_no}}</li>
			<li>{{$item->publish_year}}</li>
			<li>{{$item->tvseries->age_req}}</li>
			@if($item->subtitle == 1)
			<li>CC</li>
			<li>
				{{$popover_translations->where('key', 'subtitles')->first->value->value}}
			</li>
			@endif
		</ul>
		
		@if ( $item->detail || $item->tvseries->detail )
		<div class="main-des">
			@if ($item->detail)
				<p>{{ str_limit($item->detail, 150, '...') }}</p>
			@else
				<p>{{ str_limit($item->tvseries->detail, 150, '...') }}</p>
			@endif
		</div>
		@endif

		<div class="des-btn-block">
			<a onclick="playEpisodes({{$item->id}})" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>

			<a onclick="addWish({{$item->id}},'{{$item->type}}')" class="addwishlistbtn{{$item->id}}{{$item->type}} btn-default">{{ $auth->addedWishlist($item->id, $item->type) ? $popover_translations->where('key', 'remove from watchlist')->first->value->value : $popover_translations->where('key', 'add to watchlist')->first->value->value}}</a>
		</div>
	</div>
</div>