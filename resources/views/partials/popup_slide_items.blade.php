@foreach ( $auth->wishlist as $key => $wish )
	@if ( $wish->item && !($item_id == $wish->item->id && $item_type == $wish->item->type) )
		@if ( $wish->item->type == 'S' )
		<div class="genre-prime-slide">
			<div class="genre-slide-image protip" data-pt-placement="outside" data-pt-title="#prime-mix-description-block{{$wish->item->id}}{{$wish->item->type}}">
				<a href="{{url('show/detail',$wish->item->id)}}">
					@if($wish->item->thumbnail != null)
					<img src="{{asset('images/tvseries/thumbnails/'.$wish->item->thumbnail)}}" class="img-responsive" alt="genre-image">
					@else
					<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">
					@endif
				</a>
			</div>
			<div id="prime-mix-description-block{{$wish->item->id}}{{$wish->item->type}}" class="prime-description-block">
				<h5 class="description-heading">{{$wish->item->tvseries->title}}</h5>
				<div class="movie-rating">IMDB {{$wish->item->tvseries->rating}}</div>
				<ul class="description-list">
					<li>Season {{$wish->item->season_no}}</li>
					<li>{{$wish->item->publish_year}}</li>
					<li>{{$wish->item->tvseries->age_req}}</li>
					@if($wish->item->subtitle == 1)
					<li>CC</li>
					<li>
						{{$popover_translations->where('key', 'subtitles')->first->value->value}}
					</li>
					@endif
				</ul>

				@if ( $wish->item->detail || $wish->item->tvseries->detail )
				<div class="main-des">
					@if ( $wish->item->detail )
						<p>{{$wish->item->detail }}</p>
					@else
						<p>{{ $wish->item->tvseries->detail }}</p>
					@endif
				</div>
				@endif

				<div class="des-btn-block">
					<a onclick="playEpisodes({{$wish->item->id}})" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>

					<a onclick="addWish({{$wish->item->id}},'{{$wish->item->type}}')" class="addwishlistbtn{{$wish->item->id}}{{$wish->item->type}} btn-default">{{ $auth->addedWishlist($wish->item->id, $wish->item->type) ? $popover_translations->where('key', 'remove from watchlist')->first->value->value : $popover_translations->where('key', 'add to watchlist')->first->value->value}}</a>
				</div>
			</div>
		</div>
		@elseif($wish->item->type == 'M')
		<div class="genre-prime-slide">
			<div class="genre-slide-image protip" data-pt-placement="outside" data-pt-title="#prime-mix-description-block{{$wish->item->id}}">
				<a href="{{url('movie/detail',$wish->item->id)}}">
					@if($wish->item->thumbnail != null)
					<img src="{{asset('images/movies/thumbnails/'.$wish->item->thumbnail)}}" class="img-responsive" alt="genre-image">
					@endif
				</a>
			</div>
			<div id="prime-mix-description-block{{$wish->item->id}}" class="prime-description-block">
				<h5 class="description-heading">{{$wish->item->title}}</h5>
				<div class="movie-rating">IMDB {{$wish->item->rating}}</div>
				<ul class="description-list">
					<li>{{$wish->item->duration}} {{$popover_translations->where('key', 'mins')->first->value->value}}</li>
					<li>{{$wish->item->publish_year}}</li>
					<li>{{$wish->item->maturity_rating}}</li>
					@if($wish->item->subtitle == 1)
					<li>CC</li>
					<li>
						{{$popover_translations->where('key', 'subtitles')->first->value->value}}
					</li>
					@endif
				</ul>

				@if ( $wish->item->detail )
				<div class="main-des">
					<p>{{ str_limit($wish->item->detail, 150, '...') }}</p>
				</div>
				@endif

				<div class="des-btn-block">
					<a onclick="playVideo({{$wish->item->id}},'{{$wish->item->type}}')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text">{{$popover_translations->where('key', 'play')->first->value->value}}</span></a>
					
					@if($wish->item->trailer_url != null || $wish->item->trailer_url != '')
					<a onclick="playTrailer('{{$wish->item->trailer_url}}')" class="btn-default">{{$popover_translations->where('key', 'watch trailer')->first->value->value}}</a>
					@endif

					<a onclick="addWish({{$wish->item->id}},'{{$wish->item->type}}')" class="addwishlistbtn{{$wish->item->id}}{{$wish->item->type}} btn-default">{{ $auth->addedWishlist($wish->item->id, $wish->item->type) ? $popover_translations->where('key', 'remove from watchlist')->first->value->value : $popover_translations->where('key', 'add to watchlist')->first->value->value}}</a>
				</div>
			</div>
		</div>
		@endif
	@endif
@endforeach