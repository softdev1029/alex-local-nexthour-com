<?php
use App\Genre;
use App\AudioLanguage;

$all_items = [
	'M' => [],
	'S' => [],
];
?>

@extends('layouts.theme')

@section('main-wrapper')

<!-- main wrapper  slider -->
<section class="main-wrapper">
	<div>
		@if ( count($home_slides) )
		<div id="home-main-block" class="home-main-block">
			<div id="home-slider-one" class="home-slider-one owl-carousel owl-theme">
			@foreach($home_slides as $slide)
				@if($slide->active == 1)
				<div class="slider-block">
					<div class="slider-image">
						@if($slide->movie_id != null)
						<a href="{{url('movie/detail', $slide->movie->id)}}">
							@if ($slide->slide_image != null)
							<img src="{{asset('images/home_slider/movies/'. $slide->slide_image)}}" class="img-responsive" alt="slider-image">
							@elseif ($slide->movie->poster != null)
							<img src="{{asset('images/movies/posters/'. $slide->movie->poster)}}" class="img-responsive" alt="slider-image">
							@endif
						</a>
						@elseif($slide->tv_series_id != null && isset($slide->tvseries->seasons[0]))
						<a href="{{url('show/detail', $slide->tvseries->seasons[0]->id)}}">
							@if ($slide->slide_image != null)
							<img src="{{asset('images/home_slider/shows/'. $slide->slide_image)}}" class="img-responsive" alt="slider-image">
							@elseif ($slide->tvseries->poster != null)
							<img src="{{asset('images/tvseries/posters/'. $slide->tvseries->poster)}}" class="img-responsive" alt="slider-image">
							@endif
						</a>
						@endif
					</div>
				</div>
				@endif
			@endforeach
			</div>
		</div>
		@endif

		<!-- Continue watching -->
		@if ( count($auth->continueWatchings()) )
		<div class="genre-prime-block">
			<div class="container-fluid">
				<h5 class="section-heading">{{ $popover_translations->where('key', 'continue watching')->first->value->value }}</h5>
				<div class="genre-prime-slider owl-carousel owl-theme">
					@include ('partials.continue_watchings', ['item_id' => 0, 'item_type' => ''])
				</div>
			</div>
		</div>
		@endif

		@if ( $prime_genre_slider == 1 )
			@if ( count($all_mix) > 0 )
			<div class="genre-prime-block">
				<div class="container-fluid">
					<h5 class="section-heading">{{$home_translations->where('key', 'watch next tv series and movies')->first->value->value}}</h5>
					<div class="genre-prime-slider owl-carousel owl-theme">
						@foreach($all_mix as $item)
							@if ( $item->type == 'S' )
								@include ('partials.season_item', ['item' => $item, 'section' => 'next_tvseries_movies'])
							@elseif ( $item->type == 'M' )
								@include ('partials.movie_item', ['item' => $item, 'section' => 'next_tvseries_movies'])
							@endif
						@endforeach
					</div>
				</div>
			</div>
			@endif

			@if ( count($movies) > 0 )
			<div class="genre-prime-block">
				<div class="container-fluid">
					<h5 class="section-heading">{{$home_translations->where('key', 'watch next movies') ? $home_translations->where('key', 'watch next movies')->first->value->value : ''}}</h5>
					
					<div class="genre-prime-slider owl-carousel owl-theme">
					@foreach($movies as $item)
						@include ('partials.movie_item', ['item' => $item, 'section' => 'next_movies'])
					@endforeach
					</div>
				</div>
			</div>
			@endif

			@if ( count($tvserieses) > 0 )
			<div class="genre-prime-block">
				<div class="container-fluid">
					<h5 class="section-heading">{{$home_translations->where('key', 'watch next tv series')->first->value->value}}</h5>
					<div class="genre-prime-slider owl-carousel owl-theme">
					@foreach($tvserieses as $series)
						@foreach($series->seasons as $item)
							@include ('partials.season_item', ['item' => $item, 'section' => 'next_tvseries'])
						@endforeach
					@endforeach
					</div>
				</div>
			</div>
			@endif

			@if ( $genres )
				@foreach($genres as $genre)
					@php
						$all_movies = [];
						$fil_movies = $menu->menu_data;
						foreach ($fil_movies as $value) {
							if ( $value->movie ) {
								$all_movies[] = $value->movie;
							}
						}

						$movies = [];
						foreach ($all_movies as $item) {
							if ($item->genre_id != null && $item->genre_id != '') {
								$movie_genre_list = explode(',', $item->genre_id);
								for($i = 0; $i < count($movie_genre_list); $i++) {
									$check = Genre::find(intval($movie_genre_list[$i]));
									if ( $check && $check->id == $genre->id ) {
										if ( !in_array($item->id, $all_items['M']) ) {
											$all_items['M'][] = $item->id;

											$movies[] = $item;
										}
									}
								}
							}
						}
					@endphp

					@if ( count($movies) >= 5 )
					<div class="genre-prime-block">
						<div class="container-fluid">
							<h5 class="section-heading inline">{{$genre->name}} {{$home_translations->where('key', 'movies')->first->value->value}}</h5>
							<a href="{{url('movies/genre', $genre->id)}}" class="inline see-more"> {{$home_translations->where('key', 'view all')->first->value->value}}</a>
							
							<div class="genre-prime-slider owl-carousel owl-theme">
							@foreach($movies as $item)
								@include ('partials.movie_item', ['item' => $item, 'section' => 'genre' . $genre->id . '_movies'])
							@endforeach
							</div>
						</div>
					</div>
					@endif
				@endforeach
			@endif

			@if ( count($genres) )
				@foreach($genres as $key => $genre)
					@php
						$all_tvseries = [];
						$fil_tvserieses = $menu->menu_data;
						foreach ($fil_tvserieses as $key => $value) {
							if ( $value->tvseries ) {
								$all_tvseries[] = $value->tvseries;
							}
						}

						$seasons = [];
						foreach ($all_tvseries as $item) {
							if ($item->genre_id != null && $item->genre_id != '') {
								$tvseries_genre_list = explode(',', $item->genre_id);
								for($i = 0; $i < count($tvseries_genre_list); $i++) {
									$check = Genre::find(intval($tvseries_genre_list[$i]));
									if ( $check && $check->id == $genre->id ) {
										if ( count($item->seasons) ) {
											foreach ( $item->seasons as $season ) {
												if ( !in_array($season->id, $all_items['S']) ) {
													$all_items['S'][] = $season->id;
													$seasons[] = $season;
												}
											}
										}
									}
								}
							}
						}
					@endphp

					@if ( count($seasons) >= 5 )
					<div class="genre-prime-block">
						<div class="container-fluid">
							<h5 class="section-heading inline">{{$genre->name}} {{$home_translations->where('key', 'tv shows')->first->value->value}}</h5>
							<a href="{{url('tvseries/genre', $genre->id)}}" class="inline see-more"> {{$home_translations->where('key', 'view all')->first->value->value}}</a>
							
							<div class="genre-prime-slider owl-carousel owl-theme">
							@foreach($seasons as $item)
								@include ('partials.season_item', ['item' => $item, 'section' => 'genre' . $genre->id . '_seasons'])
							@endforeach
							</div>
						</div>
					</div>
					@endif
				@endforeach
			@endif

			@if ( count($a_languages) )
				@foreach($a_languages as $key => $lang)
					@php
						$all_movies = [];

						$fil_movies = $menu->menu_data;
						foreach ($fil_movies as $key => $value) {
							if ( $value->movie ) {
								$all_movies[] = $value->movie;
							}
						}

						$movies = [];
						foreach ($all_movies as $item) {
							if ($item->a_language != null && $item->a_language != '') {
								$movie_lang_list = explode(',', $item->a_language);
								for($i = 0; $i < count($movie_lang_list); $i++) {
									$check = AudioLanguage::find(intval($movie_lang_list[$i]));
									if ( $check && $check->id == $lang->id ) {
										$movies[] = $item;
									}
								}
							}
						}
					@endphp

					@if (count($movies) > 0)
					<div class="genre-prime-block">
						<div class="container-fluid">
							<h5 class="section-heading inline">{{$home_translations->where('key', 'movies in')->first->value->value}} {{$lang->language}}</h5>
							<a href="{{url('movies/language', $lang->id)}}" class="inline see-more"> {{$home_translations->where('key', 'view all')->first->value->value}}</a>
							
							<div class="genre-prime-slider owl-carousel owl-theme">
							@foreach($movies as $item)
								@include ('partials.movie_item', ['item' => $item, 'section' => 'language' . $lang->id . '_movies'])
							@endforeach
							</div>
						</div>
					</div>
					@endif
				@endforeach
			@endif

			@if ( count($a_languages) )
				@foreach($a_languages as $lang)
					@php
						$all_tvseries = [];
						$fil_tvserieses = $menu->menu_data;
						foreach ($fil_tvserieses as $value) {
							if ( $value->tvseries ) {
								$all_tvseries[] = $value->tvseries;
							}
						}

						$all_seasons = [];

						foreach ($all_tvseries as $tv) {
							if ( count($tv->seasons) ) {
								foreach ( $tv->seasons as $season ) {
									$all_seasons[] = $season;
								}
							} 
						}

						$seasons = [];
						foreach ($all_seasons as $item) {
							if ($item->a_language != null && $item->a_language != '') {
								$season_lang_list = explode(',', $item->a_language);
								for($i = 0; $i < count($season_lang_list); $i++) {
									$check = AudioLanguage::find(intval($season_lang_list[$i]));
									if ( $check && $check->id == $lang->id ) {
										$seasons[] = $item;
									}
								}
							}
						}
					@endphp

					@if (count($seasons) > 0)
					<div class="genre-prime-block">
						<div class="container-fluid">
							<h5 class="section-heading inline">{{$home_translations->where('key', 'tv shows in')->first->value->value}} {{$lang->language}}</h5>
							<a href="{{url('tvseries/language', $lang->id)}}" class="inline see-more"> {{$home_translations->where('key', 'view all')->first->value->value}}</a>
							
							<div class="genre-prime-slider owl-carousel owl-theme">
							@foreach($seasons as $key => $item)
								@include ('partials.season_item', ['item' => $item, 'section' => 'language' . $lang->id . '_seasons'])
							@endforeach
							</div>
						</div>
					</div>
					@endif
				@endforeach
			@endif

			@if ( count($featured_movies) > 0 )
			<div class="genre-prime-block">
				<div class="container-fluid">
					<h5 class="section-heading">{{$home_translations->where('key', 'featured')->first->value->value}} {{$home_translations->where('key', 'movies')->first->value->value}}</h5>
					
					<div class="genre-prime-slider owl-carousel owl-theme">
					@foreach($featured_movies as $item)
						@include ('partials.movie_item', ['item' => $item, 'section' => 'featured_movies'])	
					@endforeach
					</div>
				</div>
			</div>
			@endif

			@if( count($featured_seasons) > 0 )
			<div class="genre-prime-block">
				<div class="container-fluid">
					<h5 class="section-heading">{{$home_translations->where('key', 'featured')->first->value->value}} {{$home_translations->where('key', 'tv shows')->first->value->value}}</h5>
					
					<div class="genre-prime-slider owl-carousel owl-theme">
					@foreach($featured_seasons as $item)
						@include ('partials.season_item', ['item' => $item, 'section' => 'featured_seasons'])
					@endforeach
					</div>
				</div>
			</div>
			@endif
		@else
			<div class="genre-main-block">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">
							<div class="genre-dtl-block">
								<h3 class="section-heading">{{$home_translations->where('key', 'watch next tv series and movies')->first->value->value}}</h3>
								<p class="section-dtl">{{$home_translations->where('key', 'at the big screen at home')->first->value->value}}</p>
							</div>
						</div>
						<div class="col-md-9">
							<div class="genre-main-slider owl-carousel owl-theme">
							@if(isset($all_mix))
								@foreach($all_mix as $key => $item)
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
										</div>
									</div>
									@elseif($item->type == 'M')
									<div class="genre-slide">
										<div class="genre-slide-image">
											<a href="{{url('movie/detail/'.$item->id)}}">
												@if($item->thumbnail != null)
												<img src="{{asset('images/movies/thumbnails/'.$item->thumbnail)}}" class="img-responsive" alt="genre-image">
												@else
												<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
												@endif
											</a>
										</div>
										<div class="genre-slide-dtl">
											<h5 class="genre-dtl-heading"><a href="{{url('movie/detail/'.$item->id)}}">{{$item->title}}</a></h5>
										</div>
									</div>
									@endif
								@endforeach
							@endif
							</div>
						</div>
					</div>
				</div>			
			</div>

			<div class="genre-main-block">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">
							<div class="genre-dtl-block">
								<h3 class="section-heading">{{$home_translations->where('key', 'watch next movies')->first->value->value}}</h3>
								<p class="section-dtl">{{$home_translations->where('key', 'at the big screen at home')->first->value->value}}</p>
							</div>
						</div>
						<div class="col-md-9">
							<div class="genre-main-slider owl-carousel owl-theme">
							@if(isset($movies))
								@foreach($movies as $key => $movie)
								<div class="genre-slide">
									<div class="genre-slide-image">
										<a href="{{url('movie/detail/'.$movie->id)}}">
											@if($movie->thumbnail != null)
											<img src="{{asset('images/movies/thumbnails/'.$movie->thumbnail)}}" class="img-responsive" alt="genre-image">
											@else
											<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
											@endif
										</a>
									</div>
									<div class="genre-slide-dtl">
										<h5 class="genre-dtl-heading"><a href="{{url('movie/detail/'.$movie->id)}}">{{$movie->title}}</a></h5>
									</div>
								</div>
								@endforeach
							@endif
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="genre-main-block">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">
							<div class="genre-dtl-block">
								<h3 class="section-heading">{{$home_translations->where('key', 'watch next tv series')->first->value->value}}</h3>
								<p class="section-dtl">{{$home_translations->where('key', 'at the big screen at home')->first->value->value}}</p>
							</div>
						</div>
						<div class="col-md-9">
							<div class="genre-main-slider owl-carousel owl-theme">
							@if(isset($tvserieses))
								@foreach($tvserieses as $tvseries)
									@foreach($tvseries->seasons as $item)
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
										</div>
									</div>
									@endforeach  
								@endforeach
							@endif
							</div>
						</div>
					</div>
				</div>
			</div>

			@if ( count($genres) )
				@foreach($genres as $key => $genre)
					@php
						$all_movies = [];
						$fil_movies = $menu->menu_data;
						foreach ($fil_movies as $key => $value) {
							if ( $value->movie ) {
								$all_movies[] = $value->movie;
							}
						}

						$movies = [];
						foreach ($all_movies as $item) {
							if ($item->genre_id != null && $item->genre_id != '') {
								$movie_genre_list = explode(',', $item->genre_id);
								for($i = 0; $i < count($movie_genre_list); $i++) {
									$check = Genre::find(intval($movie_genre_list[$i]));
									if ( $check && $check->id == $genre->id ) {
										$movies[] = $item;
									}
								}
							}
						}
					@endphp
				
					@if (count($movies) > 0)
					<div class="genre-main-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3">
									<div class="genre-dtl-block">
										<h3 class="section-heading">{{$genre->name}} {{$home_translations->where('key', 'movies')->first->value->value}}</h3>
										<p class="section-dtl">{{$home_translations->where('key', 'at the big screen at home')->first->value->value}}</p>
										<a href="{{url('movies/genre', $genre->id)}}" class="btn-more">{{$home_translations->where('key', 'view all')->first->value->value}}</a>
									</div>
								</div>
								<div class="col-md-9">
									<div class="genre-main-slider owl-carousel owl-theme">
									@if(isset($movies))
										@foreach($movies as $key => $movie)
										<div class="genre-slide">
											<div class="genre-slide-image">
												<a href="{{url('movie/detail/'.$movie->id)}}">
													@if($movie->thumbnail != null)
													<img src="{{asset('images/movies/thumbnails/'.$movie->thumbnail)}}" class="img-responsive" alt="genre-image">
													@else
													<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
													@endif
												</a>
											</div>
											<div class="genre-slide-dtl">
												<h5 class="genre-dtl-heading"><a href="{{url('movie/detail/'.$movie->id)}}">{{$movie->title}}</a></h5>
											</div>
										</div>  
										@endforeach
									@endif
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
				@endforeach
			@endif

			@if( count($genres) )
				@foreach($genres as $key => $genre)
					@php
						$all_tvseries = [];
						$fil_tvserieses = $menu->menu_data;
						foreach ($fil_tvserieses as $key => $value) {
							if ( $value->tvseries ) {
								$all_tvseries[] = $value->tvseries;
							}
						}

						$seasons = [];
						foreach ($all_tvseries as $item) {
							if ($item->genre_id != null && $item->genre_id != '') {
								$tvseries_genre_list = explode(',', $item->genre_id);
								for($i = 0; $i < count($tvseries_genre_list); $i++) {
									$check = Genre::find(intval($tvseries_genre_list[$i]));
									if ( $check && $check->id == $genre->id ) {
										if ( count($item->seasons) ) {
											foreach ( $item->seasons as $season ) {
												$seasons[] = $season;
											}
										}
									}
								}
							}  
						}
					@endphp

					@if (count($seasons) > 0)
					<div class="genre-main-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3">
									<div class="genre-dtl-block">
										<h3 class="section-heading">{{$genre->name}} {{$home_translations->where('key', 'tv shows')->first->value->value}}</h3>
										<p class="section-dtl">{{$home_translations->where('key', 'at the big screen at home')->first->value->value}}</p>
										<a href="{{url('tvseries/genre', $genre->id)}}" class="btn-more">{{$home_translations->where('key', 'view all')->first->value->value}}</a>
									</div>
								</div>
								<div class="col-md-9">
									<div class="genre-main-slider owl-carousel owl-theme">
									@foreach($seasons as $item)
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
											</div>
										</div>
									@endforeach
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
				@endforeach
			@endif

			@if( count($a_languages) )
				@foreach($a_languages as $key => $lang)
					@php
						$all_movies = [];
						$fil_movies = $menu->menu_data;
						foreach ($fil_movies as $key => $value) {
							if ( $value->movie ) {
								$all_movies[] = $value->movie;
							}
						}

						$movies = [];
						foreach ($all_movies as $item) {
							if ($item->a_language != null && $item->a_language != '') {
								$movie_lang_list = explode(',', $item->a_language);
									for($i = 0; $i < count($movie_lang_list); $i++) {
									$check = AudioLanguage::find(intval($movie_lang_list[$i]));
									if ( $check && $check->id == $lang->id ) {
										$movies[] = $item;
									}
								}
							}
						}
					@endphp

					@if (count($movies) > 0)
					<div class="genre-main-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3">
									<div class="genre-dtl-block">
										<h3 class="section-heading">{{$home_translations->where('key', 'movies in')->first->value->value}} {{$lang->language}}</h3>
										<p class="section-dtl">{{$home_translations->where('key', 'at the big screen at home')->first->value->value}}</p>
										<a href="{{url('movies/language', $lang->id)}}" class="btn-more">{{$home_translations->where('key', 'view all')->first->value->value}}</a>
									</div>
								</div>
								<div class="col-md-9">
									<div class="genre-main-slider owl-carousel owl-theme">
									@foreach($movies as $key => $movie)
										<div class="genre-slide">
											<div class="genre-slide-image">
												<a href="{{url('movie/detail/'.$movie->id)}}">
													@if($movie->thumbnail != null)
													<img src="{{asset('images/movies/thumbnails/'.$movie->thumbnail)}}" class="img-responsive" alt="genre-image">
													@else
													<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
													@endif
												</a>
											</div>
											<div class="genre-slide-dtl">
												<h5 class="genre-dtl-heading"><a href="{{url('movie/detail/'.$movie->id)}}">{{$movie->title}}</a></h5>
											</div>
										</div>
									@endforeach
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
				@endforeach
			@endif

			@if ( count($a_languages) )
				@foreach($a_languages as $key => $lang)
					@php
						$all_tvseries = [];
						$fil_tvserieses = $menu->menu_data;
						foreach ($fil_tvserieses as $key => $value) {
							if ( $value->tvseries ) {
								$all_tvseries[] = $value->tvseries;
							}
						}

						$all_seasons = [];
						foreach ($all_tvseries as $tv) {
							if ( count($tv->seasons) ) {
								foreach ( $tv->seasons as $season ) {
									$all_seasons[] = $season;
								}
							} 
						}

						$seasons = [];
						foreach ($all_seasons as $item) {
							if ($item->a_language != null && $item->a_language != '') {
								$season_lang_list = explode(',', $item->a_language);
								for($i = 0; $i < count($season_lang_list); $i++) {
									$check = AudioLanguage::find(intval($season_lang_list[$i]));
									if ( $check && $check->id == $lang->id ) {
										$seasons[] = $item;
									}
								}
							}
						}
					@endphp

					@if (count($seasons) > 0)
					<div class="genre-main-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3">
									<div class="genre-dtl-block">
										<h3 class="section-heading">{{$home_translations->where('key', 'tv shows in')->first->value->value}} {{$lang->language}}</h3>
										<p class="section-dtl">{{$home_translations->where('key', 'at the big screen at home')->first->value->value}}</p>
										<a href="{{url('tvseries/language', $lang->id)}}" class="btn-more">{{$home_translations->where('key', 'view all')->first->value->value}}</a>
									</div>
								</div>
								<div class="col-md-9">
									<div class="genre-main-slider owl-carousel owl-theme">
									@if(isset($seasons))
										@foreach($seasons as $item)
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
											</div>
										</div>
										@endforeach  
									@endif
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif  
				@endforeach
			@endif

			@if ( count($featured_movies) > 0 )
			<div class="genre-main-block">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">
							<div class="genre-dtl-block">
								<h3 class="section-heading">{{$home_translations->where('key', 'featured')->first->value->value}} {{$home_translations->where('key', 'movies')->first->value->value}}</h3>
								<p class="section-dtl">{{$home_translations->where('key', 'at the big screen at home')->first->value->value}}</p>
							</div>
						</div>
						<div class="col-md-9">
							<div class="genre-main-slider owl-carousel owl-theme">
							@foreach($featured_movies as $key => $movie)
								<div class="genre-slide">
									<div class="genre-slide-image">
										<a href="{{url('movie/detail/'.$movie->id)}}">
											@if($movie->thumbnail != null)
											<img src="{{asset('images/movies/thumbnails/'.$movie->thumbnail)}}" class="img-responsive" alt="genre-image">
											@else
											<img src="{{asset('images/default-thumbnail.jpg')}}" class="img-responsive" alt="genre-image">  
											@endif
										</a>
									</div>
									<div class="genre-slide-dtl">
										<h5 class="genre-dtl-heading"><a href="{{url('movie/detail/'.$movie->id)}}">{{$movie->title}}</a></h5>
									</div>
								</div>  
							@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif

			@if ( count($featured_seasons) > 0 )
			<div class="genre-main-block">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">
							<div class="genre-dtl-block">
								<h3 class="section-heading">{{$home_translations->where('key', 'featured')->first->value->value}} {{$home_translations->where('key', 'tv shows')->first->value->value}}</h3>
								<p class="section-dtl">{{$home_translations->where('key', 'at the big screen at home')->first->value->value}}</p>
							</div>
						</div>
						<div class="col-md-9">
							<div class="genre-main-slider owl-carousel owl-theme">
							@foreach($featured_seasons as $item)
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
									</div>
								</div>
							@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
		@endif
	</div>

	<div id="wishlistelement"></div>
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