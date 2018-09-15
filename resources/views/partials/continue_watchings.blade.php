@foreach ( $auth->continueWatchings() as $item )
	@if ( $item && !($item_id == $item->id && $item_type == $item->type) )
		@if ( $item->type == 'S' )
			@include ('partials.season_item', ['item' => $item, 'section' => 'continue_watching'])
		@elseif($item->type == 'M')
			@include ('partials.movie_item', ['item' => $item, 'section' => 'continue_watching'])
		@endif
	@endif
@endforeach