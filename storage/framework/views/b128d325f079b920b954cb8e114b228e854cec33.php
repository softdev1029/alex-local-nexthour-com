<?php $__env->startSection('main-wrapper'); ?>
<!-- main wrapper -->
<section id="wishlistelement" class="main-wrapper main-wrapper-single-movie-prime">
	<?php if(isset($filter_video)): ?>
	<?php if(count($filter_video) > 0): ?>
	<div class="container-fluid movie-series-section search-section">
		<h5 class="movie-series-heading" style="margin-left: 45px;"><?php echo e(count($filter_video)); ?> <?php echo e($home_translations->where('key', 'found for')->first->value->value); ?> "<?php echo e($searchKey); ?>"</h5>
		<div>
		<?php $__currentLoopData = $filter_video; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<div class="movie-series-block">
			<div class="row">
				<div class="col-sm-3">
					<div class="movie-series-img">
						<?php if($item->type == 'M' && $item->thumbnail != null): ?>
						<img src="<?php echo e(asset('images/movies/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
						<?php elseif($item->type == 'M' && $item->thumbnail == null): ?>  
						<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">
						<?php elseif($item->type == 'S'): ?>
						<?php if($item->thumbnail != null): ?>
						<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
						<?php elseif($item->tvseries->thumbnail != null): ?>
						<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$item->tvseries->thumbnail)); ?>" class="img-responsive" alt="genre-image">
						<?php else: ?>
						<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
						<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-sm-7 pad-0">
					<h5 class="movie-series-heading movie-series-name">
						<?php if($item->type == 'M'): ?>
						<a href="<?php echo e(url('movie/detail', $item->id)); ?>"><?php echo e($item->title); ?></a>
						<?php elseif($item->type == 'S'): ?>
						<a href="<?php echo e(url('show/detail', $item->id)); ?>"><?php echo e($item->tvseries->title); ?></a>
						<?php endif; ?>
					</h5>
					<ul class="movie-series-des-list">
						<?php if($item->type == 'M'): ?>
						<li>IMDB <?php echo e($item->rating); ?></li>
						<?php endif; ?>
						<?php if($item->type == 'S'): ?>
						<li>IMDB <?php echo e($item->tvseries->rating); ?></li>                        
						<?php endif; ?>
						<li>
							<?php if($item->type == 'M'): ?>
							<?php echo e($item->duration); ?> <?php echo e($popover_translations->where('key', 'mins')->first->value->value); ?>

							<?php else: ?>
							<?php echo e($popover_translations->where('key', 'season')->first->value->value); ?> <?php echo e($item->season_no); ?>

							<?php endif; ?>
						</li>
						<?php if($item->type == 'M'): ?>
						<li><?php echo e($item->released); ?></li>
						<?php else: ?>
						<li><?php echo e($item->publish_year); ?></li>  
						<?php endif; ?>
						<li>
							<?php if($item->type == 'M'): ?>
							<?php echo e($item->maturity_rating); ?>

							<?php else: ?>
							<?php echo e($item->tvseries->maturity_rating); ?> 
							<?php endif; ?>
						</li>
						<?php if($item->subtitle == 1): ?>
						<li>
							<?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?>

						</li>
						<?php endif; ?>
					</ul>
					<p>
						<?php if($item->type == 'M'): ?>
						<?php echo e(str_limit($item->detail, 360)); ?>

						<?php else: ?>
						<?php if($item->detail != null || $item->detail != ''): ?>
						<?php echo e($item->detail); ?>

						<?php else: ?>  
						<?php echo e(str_limit($item->tvseries->detail, 360)); ?>                        
						<?php endif; ?>
						<?php endif; ?>
					</p>
					<div class="des-btn-block des-in-list">
					<?php if($item->type == 'M'): ?>
						<a onclick="playVideo(<?php echo e($item->id); ?>,'<?php echo e($item->type); ?>')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>

						<?php if($item->trailer_url != null || $item->trailer_url != ''): ?>
						<a onclick="playTrailer('<?php echo e($item->trailer_url); ?>')" class="btn-default"><?php echo e($popover_translations->where('key', 'watch trailer')->first->value->value); ?></a>
						<?php endif; ?>						
					<?php else: ?>
						<a onclick="playVideo(<?php echo e($item->id); ?>,'<?php echo e($item->type); ?>')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>
					<?php endif; ?>

						<a onclick="addWish(<?php echo e($item->id); ?>,'<?php echo e($item->type); ?>')" class="addwishlistbtn<?php echo e($item->id); ?><?php echo e($item->type); ?> btn-default"><?php echo e($auth->addedWishlist($item->id, $item->type) ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
</div>
<?php else: ?>
<div class="container-fluid movie-series-section search-section">
	<h5 class="movie-series-heading">0 <?php echo e($home_translations->where('key', 'found for')->first->value->value); ?> "<?php echo e($searchKey); ?>"</h5>
</div>
<?php endif; ?>
<?php endif; ?>
</section>
<!-- end main wrapper -->
<div class="video-player">
	<div class="close-btn-block text-right">
		<a class="close-btn" onclick="closeVideo()"></a>
	</div>
	<div id="my_video"></div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom-script'); ?>

<?php echo $__env->make('partials.script_play', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.theme', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>