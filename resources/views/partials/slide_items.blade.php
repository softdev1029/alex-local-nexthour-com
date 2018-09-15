@foreach($items as $key => $item)
	@if($item->type == 'S')
	<div class="genre-slide">
		<div class="genre-slide-image">
			<a href="{{url('show/detail/'.$item->id)}}">
				@if($item->thumbnail != null)
				<img src="{{asset('images/tvseries/thumbnails/'.$item->thumbnail)}}" class="img-responsive" alt="genre-image">
				@elseif($item->tvseries->thumbnail != null)
				<img src="{{asset('images/tvseries/thumbnails/'.$item->tvseries->thumbnail)}}" class="img-responsive" alt="genre-image">
				@else
				<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">
				@endif
			</a>
		</div>
		<div class="genre-slide-dtl">
			<h5 class="genre-dtl-heading"><a href="{{url('show/detail/'.$item->id)}}">{{$item->tvseries->title}}</a></h5>
			<div class="genre-small-info">{{$item->detail != null ? $item->detail : $item->tvseries->detail}}</div>
		</div>
	</div>
	@elseif($item->type == 'M')
	<div class="genre-slide">
		<div class="genre-slide-image">
			<a href="{{url('movie/detail/'.$item->id)}}">
				@if($item->thumbnail != null)
				<img src="{{asset('images/movies/thumbnails/'.$item->thumbnail)}}" class="img-responsive" alt="genre-image">
				@endif
			</a>
		</div>
		<div class="genre-slide-dtl">
			<h5 class="genre-dtl-heading"><a href="{{url('movie/detail/'.$item->id)}}">{{$item->title}}</a></h5>
		</div>
	</div>
	@endif
@endforeach