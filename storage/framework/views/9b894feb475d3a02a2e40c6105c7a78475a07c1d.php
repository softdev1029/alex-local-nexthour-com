<?php $__env->startSection('main-wrapper'); ?>
<!-- main wrapper -->
<section class="main-wrapper">
	<div class="container-fluid">
		<div class="watchlist-section">
			<h5 class="watchlist-heading"><?php echo e($header_translations->where('key', 'watchlist')->first->value->value); ?></h5>
			<div class="watchlist-btn-block">
				<div class="btn-group">
					<a href="<?php echo e(url('account/watchlist/movies')); ?>" class="<?php echo e(isset($all_movies) ? 'active' : ''); ?>"><?php echo e($home_translations->where('key', 'movies')->first->value->value); ?></a>
					<a href="<?php echo e(url('account/watchlist/shows')); ?>" class="<?php echo e(isset($all_shows) ? 'active' : ''); ?>"><?php echo e($home_translations->where('key', 'tv shows')->first->value->value); ?></a>
				</div>
			</div>
			<?php if(isset($all_shows)): ?>
			<div class="watchlist-main-block">
				<?php $__currentLoopData = $all_shows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="watchlist-block">
					<div class="watchlist-img-block protip" data-pt-placement="outside" data-pt-title="#prime-show-description-block<?php echo e($item->id); ?>">
						<a href="<?php echo e(url('show/detail',$item->id)); ?>">
							<?php if($item->thumbnail != null): ?>
							<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
							<?php elseif($item->tvseries->thumbnail != null): ?>
							<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->tvseries->thumbnail)); ?>" class="img-responsive" alt="genre-image">
							<?php else: ?>
							<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
							<?php endif; ?>
						</a>
					</div>
					<?php echo Form::open(['method' => 'DELETE', 'action' => ['WishListController@showdestroy', $item->id]]); ?>

					<?php echo Form::submit("Remove", ["class" => "remove-btn"]); ?>

					<?php echo Form::close(); ?>

					<div id="prime-show-description-block<?php echo e($item->id); ?>" class="prime-description-block">
						<h5 class="description-heading"><?php echo e($item->tvseries->title); ?></h5>
						<div class="movie-rating">IMDB <?php echo e($item->tvseries->rating); ?></div>
						<ul class="description-list">
							<li><?php echo e($popover_translations->where('key', 'season')->first->value->value); ?> <?php echo e($item->season_no); ?></li>
							<li><?php echo e($item->publish_year); ?></li>
							<li><?php echo e($item->tvseries->age_req); ?></li>
							<?php if($item->subtitle == 1): ?>
							<li>
								<?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?>

							</li>
							<?php endif; ?>
						</ul>
						<div class="main-des">
							<?php if($item->detail != null || $item->detail != ''): ?>
							<p><?php echo e($item->detail); ?></p>
							<?php else: ?>
							<p><?php echo e($item->tvseries->detail); ?></p>
							<?php endif; ?>
							<a href="#"></a>
						</div>
						<div class="des-btn-block">
							<a href="#" onclick="playVideo(<?php echo e($item->id); ?>, '<?php echo e($item->type); ?>')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>
						</div>
					</div>
				</div>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
			<?php endif; ?>
			<?php if(isset($all_movies)): ?>
			<div class="watchlist-main-block">
				<?php $__currentLoopData = $all_movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $movie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="watchlist-block">
					<div class="watchlist-img-block protip" data-pt-placement="outside" data-pt-title="#prime-description-block<?php echo e($movie->id); ?>">
						<a href="<?php echo e(url('movie/detail',$movie->id)); ?>">
							<?php if($movie->thumbnail != null || $movie->thumbnail != ''): ?>
							<img src="<?php echo e(asset('images/movies/thumbnails/'.$movie->thumbnail)); ?>" class="img-responsive" alt="genre-image">
							<?php else: ?>
							<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
							<?php endif; ?>
						</a>
					</div>
					<?php echo Form::open(['method' => 'DELETE', 'action' => ['WishListController@moviedestroy', $movie->id]]); ?>

					<?php echo Form::submit("Remove", ["class" => "remove-btn"]); ?>

					<?php echo Form::close(); ?>

					<div id="prime-description-block<?php echo e($movie->id); ?>" class="prime-description-block">
						<div class="prime-description-under-block">                          
							<h5 class="description-heading"><?php echo e($movie->title); ?></h5>
							<div class="movie-rating">IMDB <?php echo e($movie->rating); ?></div>
							<ul class="description-list">
								<li><?php echo e($movie->duration); ?> <?php echo e($popover_translations->where('key', 'mins')->first->value->value); ?></li>
								<li><?php echo e($movie->publish_year); ?></li>
								<li><?php echo e($movie->maturity_rating); ?></li>
								<?php if($movie->subtitle == 1): ?>
								<li>
									<?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?>

								</li>
								<?php endif; ?>
							</ul>
							<div class="main-des">
								<p><?php echo e($movie->detail); ?></p>
								<a href="#"></a>
							</div>
							<div class="des-btn-block">
								<a href="#" onclick="playVideo(<?php echo e($movie->id); ?>, '<?php echo e($movie->type); ?>')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>
								<?php if($movie->trailer_url != null || $movie->trailer_url != ''): ?>
								<a href="#" onclick="playTrailer('<?php echo e($movie->trailer_url); ?>')" class="btn-default"><?php echo e($popover_translations->where('key', 'watch trailer')->first->value->value); ?></a>
								<?php endif; ?>
							</div>
						</div>  
					</div>
				</div>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
			<?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom-script'); ?>

<?php echo $__env->make('partials.script_play', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.theme', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>