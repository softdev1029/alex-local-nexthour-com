<?php $__env->startSection('main-wrapper'); ?>
<!-- main wrapper -->
<section class="main-wrapper main-wrapper-single-movie-prime">
	<div class="background-main-poster-overlay">
	<?php if($movie->poster != null): ?>
		<div class="background-main-poster col-md-offset-4 col-md-6" style="background-image: url('<?php echo e(asset('images/movies/posters/'.$movie->poster)); ?>');">
	<?php else: ?>
		<div class="background-main-poster col-md-offset-4 col-md-6" style="background-image: url('<?php echo e(asset('images/default-poster.jpg')); ?>');">
	<?php endif; ?>
		</div>
		<div class="overlay-bg gredient-overlay-right"></div>
		<div class="overlay-bg"></div>
	</div>

	<div id="full-movie-dtl-main-block" class="full-movie-dtl-main-block">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8">
					<div class="full-movie-dtl-block">
						<h2 class="section-heading"><?php echo e($movie->title); ?></h2>
						<div class="imdb-ratings-block">
							<ul>
								<li><?php echo e($movie->publish_year); ?></li>
								<li><?php echo e($movie->duration); ?> <?php echo e($popover_translations->where('key', 'mins')->first->value->value); ?></li>
								<li><?php echo e($movie->maturity_rating); ?></li>
								<li>IMDB <?php echo e($movie->rating); ?></li>
								<?php if( $movie->subtitle && $sub_titles ): ?>
								<li>CC</li>
								<li><?php echo e($sub_titles); ?></li>
								<?php endif; ?>
							</ul>
						</div>

						<?php if( $movie->detail ): ?>
						<p>
							<?php echo e(str_limit($movie->detail, 150, '...')); ?>

						</p>
						<?php endif; ?>
					</div>
					<div class="screen-casting-dtl">
						<ul class="casting-headers">
							<li><?php echo e($home_translations->where('key', 'directors')->first->value->value); ?></li>
							<li><?php echo e($home_translations->where('key', 'starring')->first->value->value); ?></li>
							<li><?php echo e($home_translations->where('key', 'genres')->first->value->value); ?></li>
							<li><?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?></li>
							<li><?php echo e($home_translations->where('key', 'audio languages')->first->value->value); ?></li>
						</ul>
						<ul class="casting-dtl">
							<li><?php echo $directors ?: '-'; ?></li>
							<li><?php echo $actors ?: '-'; ?></li>
							<li><?php echo $genreString ?: '-'; ?></li>
							<li>
							<?php if( $movie->subtitle && $sub_titles ): ?>
								<?php echo e($sub_titles); ?>

							<?php else: ?>
								-
							<?php endif; ?>
							</li>
							<li><?php echo $audio_languages ?: '-'; ?></li>
						</ul>
					</div>
					<div id="wishlistelement" class="screen-play-btn-block">
						<a onclick="playVideo(<?php echo e($movie->id); ?>,'<?php echo e($movie->type); ?>')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>
						<div class="btn-group btn-block">
							<?php if($movie->trailer_url != null || $movie->trailer_url != ''): ?>
							<a onclick="playTrailer('<?php echo e($movie->trailer_url); ?>')" class="btn btn-default"><?php echo e($popover_translations->where('key', 'watch trailer')->first->value->value); ?></a>
							<?php endif; ?>

							<a onclick="addWish(<?php echo e($movie->id); ?>,'<?php echo e($movie->type); ?>')" class="addwishlistbtn<?php echo e($movie->id); ?><?php echo e($movie->type); ?> btn-default"><?php echo e($auth->addedWishlist($movie->id, $movie->type) ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)); ?></a>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="poster-thumbnail-block">
						<?php if($movie->thumbnail != null || $movie->thumbnail != ''): ?>
						<img src="<?php echo e(asset('images/movies/thumbnails/'.$movie->thumbnail)); ?>" class="img-responsive" alt="genre-image">
						<?php else: ?>
						<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">
						<?php endif; ?>
					</div>
				</div>
			</div>	
		</div>
	</div>

	<!-- movie series -->
	<?php if( count($movie->movie_series) > 0 && $movie->series != 1 ): ?>
	<div class="container-fluid movie-series-section search-section">
		<h5 class="movie-series-heading">Series <?php echo e(count($movie->movie_series)); ?></h5>
		<div>
		<?php $__currentLoopData = $movie->movie_series; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $series): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

			<div class="movie-series-block">
				<div class="row">
					<div class="col-sm-3">
						<div class="movie-series-img">
							<?php if($single_series->thumbnail != null || $single_series->thumbnail != ''): ?>
							<img src="<?php echo e(asset('images/movies/thumbnails/'.$single_series->thumbnail)); ?>" class="img-responsive" alt="genre-image">
							<?php endif; ?>
						</div>
					</div>
					<div class="col-sm-7 pad-0">
						<h5 class="movie-series-heading movie-series-name"><a href="<?php echo e(url('movie/detail', $single_series->id)); ?>"><?php echo e($single_series->title); ?></h5>
							<ul class="movie-series-des-list">
								<li>IMDB <?php echo e($single_series->rating); ?></li>
								<li><?php echo e($single_series->duration); ?> <?php echo e($popover_translations->where('key', 'mins')->first->value->value); ?></li>
								<li><?php echo e($single_series->publish_year); ?></li>
								<li><?php echo e($single_series->maturity_rating); ?></li>
								<?php if($single_series->subtitle == 1): ?>
								<li><?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?></li>
								<?php endif; ?>
							</ul>
							<p>
								<?php echo e($single_series->detail); ?>

							</p>
							<div class="des-btn-block des-in-list">
								<a onclick="playEpisodes(<?php echo e($single_series->id); ?>)" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>

								<?php if($single_series->trailer_url != null || $single_series->trailer_url != ''): ?>
								<a onclick="playTrailer('<?php echo e($single_series->trailer_url); ?>')" class="btn-default"><?php echo e($popover_translations->where('key', 'watch trailer')->first->value->value); ?></a>
								<?php endif; ?>

								<a onclick="addWish(<?php echo e($single_series->id); ?>,'<?php echo e($single_series->type); ?>')" class="addwishlistbtn<?php echo e($single_series->id); ?><?php echo e($single_series->type); ?> btn-default"><?php echo e($auth->addedWishlist($single_series->id, $single_series->type) ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)); ?></a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if( $movie->series == 1 && count($filter_series) > 0 ): ?>
	<div class="container-fluid movie-series-section search-section">
		<h5 class="movie-series-heading" style="margin-left: 45px;"><?php echo e($home_translations->where('key', 'series')->first->value->value); ?> <?php echo e(count($filter_series)); ?></h5>
		<div>
		<?php $__currentLoopData = $filter_series; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $series): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="movie-series-block">
				<div class="row">
					<div class="col-sm-3">
						<div class="movie-series-img">
							<?php if($series->thumbnail != null): ?>
							<img src="<?php echo e(asset('images/movies/thumbnails/'.$series->thumbnail)); ?>" class="img-responsive" alt="genre-image">
							<?php endif; ?>
						</div>
					</div>
					<div class="col-sm-7 pad-0">
						<h5 class="movie-series-heading movie-series-name"><a href="<?php echo e(url('movie/detail', $series->id)); ?>"><?php echo e($series->title); ?></a></h5>
						<ul class="movie-series-des-list">
							<li>IMDB <?php echo e($series->rating); ?></li>
							<li><?php echo e($series->duration); ?> <?php echo e($popover_translations->where('key', 'mins')->first->value->value); ?></li>
							<li><?php echo e($series->publish_year); ?></li>
							<li><?php echo e($series->maturity_rating); ?></li>
							<?php if($series->subtitle == 1): ?>
							<li><?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?></li>
							<?php endif; ?>
						</ul>

						<?php if( $series->detail ): ?>
						<p>
							<?php echo e(str_limit($series->detail, 150, '...')); ?>

						</p>
						<?php endif; ?>

						<div class="des-btn-block des-in-list">
							<a onclick="playEpisodes(<?php echo e($series->id); ?>)" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>

							<?php if($series->trailer_url != null || $series->trailer_url != ''): ?>
							<a onclick="playTrailer('<?php echo e($series->trailer_url); ?>')" class="btn-default"><?php echo e($popover_translations->where('key', 'watch trailer')->first->value->value); ?></a>
							<?php endif; ?>

							<a onclick="addWish(<?php echo e($series->id); ?>,'<?php echo e($series->type); ?>')" class="addwishlistbtn<?php echo e($series->id); ?><?php echo e($series->type); ?> btn-default"><?php echo e($auth->addedWishlist($series->id, $series->type) ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)); ?></a>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	</div>
	<?php endif; ?>
	<!-- end movie series -->

	<?php if( $auth->wishlist->count() ): ?>
	<div class="genre-prime-block">
		<div class="container-fluid">
			<h5 class="section-heading"><?php echo e($home_translations->where('key', 'customers also watched')->first->value->value); ?></h5>
			<div class="genre-prime-slider owl-carousel owl-theme">
				<?php echo $__env->make('partials.popup_slide_items', ['item_id' => $movie->id, 'item_type' => $movie->type], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom-script'); ?>

<?php echo $__env->make('partials.script_play', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.theme', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>