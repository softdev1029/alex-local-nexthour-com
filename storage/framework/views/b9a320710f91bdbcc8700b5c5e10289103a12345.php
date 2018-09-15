<?php
use App\Genre;
use App\AudioLanguage;

$all_items = [
	'M' => [],
	'S' => [],
];
?>



<?php $__env->startSection('main-wrapper'); ?>

<!-- main wrapper  slider -->
<section class="main-wrapper">
	<div>
		<!-- Continue watching -->
		<?php if( count($auth->continueWatchings()) ): ?>
		<div class="genre-prime-block" style="padding-top: 30px;">
			<div class="container-fluid">
				<h5 class="section-heading"><?php echo e($popover_translations->where('key', 'continue watching')->first->value->value); ?></h5>
				<div class="genre-prime-slider owl-carousel owl-theme">
					<?php echo $__env->make('partials.continue_watchings', ['item_id' => 0, 'item_type' => ''], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if( count($home_slides) ): ?>
		<div id="home-main-block" class="home-main-block">
			<div id="home-slider-one" class="home-slider-one owl-carousel owl-theme">
			<?php $__currentLoopData = $home_slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<?php if($slide->active == 1): ?>
				<div class="slider-block">
					<div class="slider-image">
						<?php if($slide->movie_id != null): ?>
						<a href="<?php echo e(url('movie/detail', $slide->movie->id)); ?>">
							<?php if($slide->slide_image != null): ?>
							<img src="<?php echo e(asset('images/home_slider/'. $slide->slide_image)); ?>" class="img-responsive" alt="slider-image">
							<?php elseif($slide->movie->poster != null): ?>
							<img src="<?php echo e(asset('images/movies/posters/'. $slide->movie->poster)); ?>" class="img-responsive" alt="slider-image">
							<?php endif; ?>
						</a>
						<?php elseif($slide->tv_series_id != null && isset($slide->tvseries->seasons[0])): ?>
						<a href="<?php echo e(url('show/detail', $slide->tvseries->seasons[0]->id)); ?>">
							<?php if($slide->slide_image != null): ?>
							<img src="<?php echo e(asset('images/home_slider/'. $slide->slide_image)); ?>" class="img-responsive" alt="slider-image">
							<?php elseif($slide->tvseries->poster != null): ?>
							<img src="<?php echo e(asset('images/tvseries/posters/'. $slide->tvseries->poster)); ?>" class="img-responsive" alt="slider-image">
							<?php endif; ?>
						</a>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
		</div>
		<?php endif; ?>

		<?php if( $prime_genre_slider == 1 ): ?>
			<?php if( count($all_mix) > 0 ): ?>
			<div class="genre-prime-block">
				<div class="container-fluid">
					<h5 class="section-heading"><?php echo e($home_translations->where('key', 'watch next tv series and movies')->first->value->value); ?></h5>
					<div class="genre-prime-slider owl-carousel owl-theme">
						<?php $__currentLoopData = $all_mix; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php if( $item->type == 'S' ): ?>
								<?php echo $__env->make('partials.season_item', ['item' => $item, 'section' => 'next_tvseries_movies'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php elseif( $item->type == 'M' ): ?>
								<?php echo $__env->make('partials.movie_item', ['item' => $item, 'section' => 'next_tvseries_movies'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php if( count($movies) > 0 ): ?>
			<div class="genre-prime-block">
				<div class="container-fluid">
					<h5 class="section-heading"><?php echo e($home_translations->where('key', 'watch next movies') ? $home_translations->where('key', 'watch next movies')->first->value->value : ''); ?></h5>
					
					<div class="genre-prime-slider owl-carousel owl-theme">
					<?php $__currentLoopData = $movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php echo $__env->make('partials.movie_item', ['item' => $item, 'section' => 'next_movies'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php if( count($tvserieses) > 0 ): ?>
			<div class="genre-prime-block">
				<div class="container-fluid">
					<h5 class="section-heading"><?php echo e($home_translations->where('key', 'watch next tv series')->first->value->value); ?></h5>
					<div class="genre-prime-slider owl-carousel owl-theme">
					<?php $__currentLoopData = $tvserieses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $series): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php $__currentLoopData = $series->seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php echo $__env->make('partials.season_item', ['item' => $item, 'section' => 'next_tvseries'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php if( $genres ): ?>
				<?php $__currentLoopData = $genres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
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
					?>

					<?php if( count($movies) >= 5 ): ?>
					<div class="genre-prime-block">
						<div class="container-fluid">
							<h5 class="section-heading inline"><?php echo e($genre->name); ?> <?php echo e($home_translations->where('key', 'movies')->first->value->value); ?></h5>
							<a href="<?php echo e(url('movies/genre', $genre->id)); ?>" class="inline see-more"> <?php echo e($home_translations->where('key', 'view all')->first->value->value); ?></a>
							
							<div class="genre-prime-slider owl-carousel owl-theme">
							<?php $__currentLoopData = $movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php echo $__env->make('partials.movie_item', ['item' => $item, 'section' => 'genre' . $genre->id . '_movies'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						</div>
					</div>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php endif; ?>

			<?php if( count($genres) ): ?>
				<?php $__currentLoopData = $genres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
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
					?>

					<?php if( count($seasons) >= 5 ): ?>
					<div class="genre-prime-block">
						<div class="container-fluid">
							<h5 class="section-heading inline"><?php echo e($genre->name); ?> <?php echo e($home_translations->where('key', 'tv shows')->first->value->value); ?></h5>
							<a href="<?php echo e(url('tvseries/genre', $genre->id)); ?>" class="inline see-more"> <?php echo e($home_translations->where('key', 'view all')->first->value->value); ?></a>
							
							<div class="genre-prime-slider owl-carousel owl-theme">
							<?php $__currentLoopData = $seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php echo $__env->make('partials.season_item', ['item' => $item, 'section' => 'genre' . $genre->id . '_seasons'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						</div>
					</div>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php endif; ?>

			<?php if( count($a_languages) ): ?>
				<?php $__currentLoopData = $a_languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
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
					?>

					<?php if(count($movies) > 0): ?>
					<div class="genre-prime-block">
						<div class="container-fluid">
							<h5 class="section-heading inline"><?php echo e($home_translations->where('key', 'movies in')->first->value->value); ?> <?php echo e($lang->language); ?></h5>
							<a href="<?php echo e(url('movies/language', $lang->id)); ?>" class="inline see-more"> <?php echo e($home_translations->where('key', 'view all')->first->value->value); ?></a>
							
							<div class="genre-prime-slider owl-carousel owl-theme">
							<?php $__currentLoopData = $movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php echo $__env->make('partials.movie_item', ['item' => $item, 'section' => 'language' . $lang->id . '_movies'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						</div>
					</div>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php endif; ?>

			<?php if( count($a_languages) ): ?>
				<?php $__currentLoopData = $a_languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
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
					?>

					<?php if(count($seasons) > 0): ?>
					<div class="genre-prime-block">
						<div class="container-fluid">
							<h5 class="section-heading inline"><?php echo e($home_translations->where('key', 'tv shows in')->first->value->value); ?> <?php echo e($lang->language); ?></h5>
							<a href="<?php echo e(url('tvseries/language', $lang->id)); ?>" class="inline see-more"> <?php echo e($home_translations->where('key', 'view all')->first->value->value); ?></a>
							
							<div class="genre-prime-slider owl-carousel owl-theme">
							<?php $__currentLoopData = $seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php echo $__env->make('partials.season_item', ['item' => $item, 'section' => 'language' . $lang->id . '_seasons'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						</div>
					</div>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php endif; ?>

			<?php if( count($featured_movies) > 0 ): ?>
			<div class="genre-prime-block">
				<div class="container-fluid">
					<h5 class="section-heading"><?php echo e($home_translations->where('key', 'featured')->first->value->value); ?> <?php echo e($home_translations->where('key', 'movies')->first->value->value); ?></h5>
					
					<div class="genre-prime-slider owl-carousel owl-theme">
					<?php $__currentLoopData = $featured_movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php echo $__env->make('partials.movie_item', ['item' => $item, 'section' => 'featured_movies'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>	
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php if( count($featured_seasons) > 0 ): ?>
			<div class="genre-prime-block">
				<div class="container-fluid">
					<h5 class="section-heading"><?php echo e($home_translations->where('key', 'featured')->first->value->value); ?> <?php echo e($home_translations->where('key', 'tv shows')->first->value->value); ?></h5>
					
					<div class="genre-prime-slider owl-carousel owl-theme">
					<?php $__currentLoopData = $featured_seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php echo $__env->make('partials.season_item', ['item' => $item, 'section' => 'featured_seasons'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
				</div>
			</div>
			<?php endif; ?>
		<?php else: ?>
			<div class="genre-main-block">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">
							<div class="genre-dtl-block">
								<h3 class="section-heading"><?php echo e($home_translations->where('key', 'watch next tv series and movies')->first->value->value); ?></h3>
								<p class="section-dtl"><?php echo e($home_translations->where('key', 'at the big screen at home')->first->value->value); ?></p>
							</div>
						</div>
						<div class="col-md-9">
							<div class="genre-main-slider owl-carousel owl-theme">
							<?php if(isset($all_mix)): ?>
								<?php $__currentLoopData = $all_mix; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php if($item->type == 'S'): ?>
									<div class="genre-slide">
										<div class="genre-slide-image">
											<a href="<?php echo e(url('show/detail/'.$item->id)); ?>">
												<?php if($item->thumbnail != null): ?>
												<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
												<?php elseif($item->tvseries->thumbnail != null): ?>
												<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->tvseries->thumbnail)); ?>" class="img-responsive" alt="genre-image">
												<?php else: ?>
												<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
												<?php endif; ?>
											</a>
										</div>
										<div class="genre-slide-dtl">
											<h5 class="genre-dtl-heading"><a href="<?php echo e(url('show/detail/'.$item->id)); ?>"><?php echo e($item->tvseries->title); ?></a></h5>
										</div>
									</div>
									<?php elseif($item->type == 'M'): ?>
									<div class="genre-slide">
										<div class="genre-slide-image">
											<a href="<?php echo e(url('movie/detail/'.$item->id)); ?>">
												<?php if($item->thumbnail != null): ?>
												<img src="<?php echo e(asset('images/movies/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
												<?php else: ?>
												<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
												<?php endif; ?>
											</a>
										</div>
										<div class="genre-slide-dtl">
											<h5 class="genre-dtl-heading"><a href="<?php echo e(url('movie/detail/'.$item->id)); ?>"><?php echo e($item->title); ?></a></h5>
										</div>
									</div>
									<?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endif; ?>
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
								<h3 class="section-heading"><?php echo e($home_translations->where('key', 'watch next movies')->first->value->value); ?></h3>
								<p class="section-dtl"><?php echo e($home_translations->where('key', 'at the big screen at home')->first->value->value); ?></p>
							</div>
						</div>
						<div class="col-md-9">
							<div class="genre-main-slider owl-carousel owl-theme">
							<?php if(isset($movies)): ?>
								<?php $__currentLoopData = $movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $movie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<div class="genre-slide">
									<div class="genre-slide-image">
										<a href="<?php echo e(url('movie/detail/'.$movie->id)); ?>">
											<?php if($movie->thumbnail != null): ?>
											<img src="<?php echo e(asset('images/movies/thumbnails/'.$movie->thumbnail)); ?>" class="img-responsive" alt="genre-image">
											<?php else: ?>
											<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
											<?php endif; ?>
										</a>
									</div>
									<div class="genre-slide-dtl">
										<h5 class="genre-dtl-heading"><a href="<?php echo e(url('movie/detail/'.$movie->id)); ?>"><?php echo e($movie->title); ?></a></h5>
									</div>
								</div>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endif; ?>
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
								<h3 class="section-heading"><?php echo e($home_translations->where('key', 'watch next tv series')->first->value->value); ?></h3>
								<p class="section-dtl"><?php echo e($home_translations->where('key', 'at the big screen at home')->first->value->value); ?></p>
							</div>
						</div>
						<div class="col-md-9">
							<div class="genre-main-slider owl-carousel owl-theme">
							<?php if(isset($tvserieses)): ?>
								<?php $__currentLoopData = $tvserieses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tvseries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<?php $__currentLoopData = $tvseries->seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<div class="genre-slide">
										<div class="genre-slide-image">
											<a href="<?php echo e(url('show/detail/'.$item->id)); ?>">
												<?php if($item->thumbnail != null): ?>
												<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
												<?php elseif($item->tvseries->thumbnail != null): ?>
												<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->tvseries->thumbnail)); ?>" class="img-responsive" alt="genre-image">
												<?php else: ?>
												<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
												<?php endif; ?>
											</a>
										</div>
										<div class="genre-slide-dtl">
											<h5 class="genre-dtl-heading"><a href="<?php echo e(url('show/detail/'.$item->id)); ?>"><?php echo e($item->tvseries->title); ?></a></h5>
										</div>
									</div>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php if( count($genres) ): ?>
				<?php $__currentLoopData = $genres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
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
					?>
				
					<?php if(count($movies) > 0): ?>
					<div class="genre-main-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3">
									<div class="genre-dtl-block">
										<h3 class="section-heading"><?php echo e($genre->name); ?> <?php echo e($home_translations->where('key', 'movies')->first->value->value); ?></h3>
										<p class="section-dtl"><?php echo e($home_translations->where('key', 'at the big screen at home')->first->value->value); ?></p>
										<a href="<?php echo e(url('movies/genre', $genre->id)); ?>" class="btn-more"><?php echo e($home_translations->where('key', 'view all')->first->value->value); ?></a>
									</div>
								</div>
								<div class="col-md-9">
									<div class="genre-main-slider owl-carousel owl-theme">
									<?php if(isset($movies)): ?>
										<?php $__currentLoopData = $movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $movie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<div class="genre-slide">
											<div class="genre-slide-image">
												<a href="<?php echo e(url('movie/detail/'.$movie->id)); ?>">
													<?php if($movie->thumbnail != null): ?>
													<img src="<?php echo e(asset('images/movies/thumbnails/'.$movie->thumbnail)); ?>" class="img-responsive" alt="genre-image">
													<?php else: ?>
													<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
													<?php endif; ?>
												</a>
											</div>
											<div class="genre-slide-dtl">
												<h5 class="genre-dtl-heading"><a href="<?php echo e(url('movie/detail/'.$movie->id)); ?>"><?php echo e($movie->title); ?></a></h5>
											</div>
										</div>  
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php endif; ?>

			<?php if( count($genres) ): ?>
				<?php $__currentLoopData = $genres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
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
					?>

					<?php if(count($seasons) > 0): ?>
					<div class="genre-main-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3">
									<div class="genre-dtl-block">
										<h3 class="section-heading"><?php echo e($genre->name); ?> <?php echo e($home_translations->where('key', 'tv shows')->first->value->value); ?></h3>
										<p class="section-dtl"><?php echo e($home_translations->where('key', 'at the big screen at home')->first->value->value); ?></p>
										<a href="<?php echo e(url('tvseries/genre', $genre->id)); ?>" class="btn-more"><?php echo e($home_translations->where('key', 'view all')->first->value->value); ?></a>
									</div>
								</div>
								<div class="col-md-9">
									<div class="genre-main-slider owl-carousel owl-theme">
									<?php $__currentLoopData = $seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<div class="genre-slide">
											<div class="genre-slide-image">
												<a href="<?php echo e(url('show/detail/'.$item->id)); ?>">
													<?php if($item->thumbnail != null): ?>
													<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
													<?php elseif($item->tvseries->thumbnail != null): ?>
													<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->tvseries->thumbnail)); ?>" class="img-responsive" alt="genre-image">
													<?php else: ?>
													<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
													<?php endif; ?>
												</a>
											</div>
											<div class="genre-slide-dtl">
												<h5 class="genre-dtl-heading"><a href="<?php echo e(url('show/detail/'.$item->id)); ?>"><?php echo e($item->tvseries->title); ?></a></h5>
											</div>
										</div>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php endif; ?>

			<?php if( count($a_languages) ): ?>
				<?php $__currentLoopData = $a_languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
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
					?>

					<?php if(count($movies) > 0): ?>
					<div class="genre-main-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3">
									<div class="genre-dtl-block">
										<h3 class="section-heading"><?php echo e($home_translations->where('key', 'movies in')->first->value->value); ?> <?php echo e($lang->language); ?></h3>
										<p class="section-dtl"><?php echo e($home_translations->where('key', 'at the big screen at home')->first->value->value); ?></p>
										<a href="<?php echo e(url('movies/language', $lang->id)); ?>" class="btn-more"><?php echo e($home_translations->where('key', 'view all')->first->value->value); ?></a>
									</div>
								</div>
								<div class="col-md-9">
									<div class="genre-main-slider owl-carousel owl-theme">
									<?php $__currentLoopData = $movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $movie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<div class="genre-slide">
											<div class="genre-slide-image">
												<a href="<?php echo e(url('movie/detail/'.$movie->id)); ?>">
													<?php if($movie->thumbnail != null): ?>
													<img src="<?php echo e(asset('images/movies/thumbnails/'.$movie->thumbnail)); ?>" class="img-responsive" alt="genre-image">
													<?php else: ?>
													<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
													<?php endif; ?>
												</a>
											</div>
											<div class="genre-slide-dtl">
												<h5 class="genre-dtl-heading"><a href="<?php echo e(url('movie/detail/'.$movie->id)); ?>"><?php echo e($movie->title); ?></a></h5>
											</div>
										</div>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php endif; ?>

			<?php if( count($a_languages) ): ?>
				<?php $__currentLoopData = $a_languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
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
					?>

					<?php if(count($seasons) > 0): ?>
					<div class="genre-main-block">
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-3">
									<div class="genre-dtl-block">
										<h3 class="section-heading"><?php echo e($home_translations->where('key', 'tv shows in')->first->value->value); ?> <?php echo e($lang->language); ?></h3>
										<p class="section-dtl"><?php echo e($home_translations->where('key', 'at the big screen at home')->first->value->value); ?></p>
										<a href="<?php echo e(url('tvseries/language', $lang->id)); ?>" class="btn-more"><?php echo e($home_translations->where('key', 'view all')->first->value->value); ?></a>
									</div>
								</div>
								<div class="col-md-9">
									<div class="genre-main-slider owl-carousel owl-theme">
									<?php if(isset($seasons)): ?>
										<?php $__currentLoopData = $seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<div class="genre-slide">
											<div class="genre-slide-image">
												<a href="<?php echo e(url('show/detail/'.$item->id)); ?>">
													<?php if($item->thumbnail != null): ?>
													<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
													<?php elseif($item->tvseries->thumbnail != null): ?>
													<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->tvseries->thumbnail)); ?>" class="img-responsive" alt="genre-image">
													<?php else: ?>
													<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
													<?php endif; ?>
												</a>
											</div>
											<div class="genre-slide-dtl">
												<h5 class="genre-dtl-heading"><a href="<?php echo e(url('show/detail/'.$item->id)); ?>"><?php echo e($item->tvseries->title); ?></a></h5>
											</div>
										</div>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
									<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>  
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php endif; ?>

			<?php if( count($featured_movies) > 0 ): ?>
			<div class="genre-main-block">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">
							<div class="genre-dtl-block">
								<h3 class="section-heading"><?php echo e($home_translations->where('key', 'featured')->first->value->value); ?> <?php echo e($home_translations->where('key', 'movies')->first->value->value); ?></h3>
								<p class="section-dtl"><?php echo e($home_translations->where('key', 'at the big screen at home')->first->value->value); ?></p>
							</div>
						</div>
						<div class="col-md-9">
							<div class="genre-main-slider owl-carousel owl-theme">
							<?php $__currentLoopData = $featured_movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $movie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<div class="genre-slide">
									<div class="genre-slide-image">
										<a href="<?php echo e(url('movie/detail/'.$movie->id)); ?>">
											<?php if($movie->thumbnail != null): ?>
											<img src="<?php echo e(asset('images/movies/thumbnails/'.$movie->thumbnail)); ?>" class="img-responsive" alt="genre-image">
											<?php else: ?>
											<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
											<?php endif; ?>
										</a>
									</div>
									<div class="genre-slide-dtl">
										<h5 class="genre-dtl-heading"><a href="<?php echo e(url('movie/detail/'.$movie->id)); ?>"><?php echo e($movie->title); ?></a></h5>
									</div>
								</div>  
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php if( count($featured_seasons) > 0 ): ?>
			<div class="genre-main-block">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3">
							<div class="genre-dtl-block">
								<h3 class="section-heading"><?php echo e($home_translations->where('key', 'featured')->first->value->value); ?> <?php echo e($home_translations->where('key', 'tv shows')->first->value->value); ?></h3>
								<p class="section-dtl"><?php echo e($home_translations->where('key', 'at the big screen at home')->first->value->value); ?></p>
							</div>
						</div>
						<div class="col-md-9">
							<div class="genre-main-slider owl-carousel owl-theme">
							<?php $__currentLoopData = $featured_seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<div class="genre-slide">
									<div class="genre-slide-image">
										<a href="<?php echo e(url('show/detail/'.$item->id)); ?>">
											<?php if($item->thumbnail != null): ?>
											<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
											<?php elseif($item->tvseries->thumbnail != null): ?>
											<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->tvseries->thumbnail)); ?>" class="img-responsive" alt="genre-image">
											<?php else: ?>
											<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
											<?php endif; ?>
										</a>
									</div>
									<div class="genre-slide-dtl">
										<h5 class="genre-dtl-heading"><a href="<?php echo e(url('show/detail/'.$item->id)); ?>"><?php echo e($item->tvseries->title); ?></a></h5>
									</div>
								</div>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
		<?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom-script'); ?>

<?php echo $__env->make('partials.script_play', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.theme', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>