<?php $__currentLoopData = $auth->wishlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $wish): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<?php if( $wish->item && !($item_id == $wish->item->id && $item_type == $wish->item->type) ): ?>
		<?php if( $wish->item->type == 'S' ): ?>
		<div class="genre-prime-slide">
			<div class="genre-slide-image protip" data-pt-placement="outside" data-pt-title="#prime-mix-description-block<?php echo e($wish->item->id); ?><?php echo e($wish->item->type); ?>">
				<a href="<?php echo e(url('show/detail',$wish->item->id)); ?>">
					<?php if($wish->item->thumbnail != null): ?>
					<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$wish->item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
					<?php else: ?>
					<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">
					<?php endif; ?>
				</a>
			</div>
			<div id="prime-mix-description-block<?php echo e($wish->item->id); ?><?php echo e($wish->item->type); ?>" class="prime-description-block">
				<h5 class="description-heading"><?php echo e($wish->item->tvseries->title); ?></h5>
				<div class="movie-rating">IMDB <?php echo e($wish->item->tvseries->rating); ?></div>
				<ul class="description-list">
					<li>Season <?php echo e($wish->item->season_no); ?></li>
					<li><?php echo e($wish->item->publish_year); ?></li>
					<li><?php echo e($wish->item->tvseries->age_req); ?></li>
					<?php if($wish->item->subtitle == 1): ?>
					<li>CC</li>
					<li>
						<?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?>

					</li>
					<?php endif; ?>
				</ul>

				<?php if( $wish->item->detail || $wish->item->tvseries->detail ): ?>
				<div class="main-des">
					<?php if( $wish->item->detail ): ?>
						<p><?php echo e($wish->item->detail); ?></p>
					<?php else: ?>
						<p><?php echo e($wish->item->tvseries->detail); ?></p>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<div class="des-btn-block">
					<a onclick="playEpisodes(<?php echo e($wish->item->id); ?>)" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>

					<a onclick="addWish(<?php echo e($wish->item->id); ?>,'<?php echo e($wish->item->type); ?>')" class="addwishlistbtn<?php echo e($wish->item->id); ?><?php echo e($wish->item->type); ?> btn-default"><?php echo e($auth->addedWishlist($wish->item->id, $wish->item->type) ? $popover_translations->where('key', 'remove from watchlist')->first->value->value : $popover_translations->where('key', 'add to watchlist')->first->value->value); ?></a>
				</div>
			</div>
		</div>
		<?php elseif($wish->item->type == 'M'): ?>
		<div class="genre-prime-slide">
			<div class="genre-slide-image protip" data-pt-placement="outside" data-pt-title="#prime-mix-description-block<?php echo e($wish->item->id); ?>">
				<a href="<?php echo e(url('movie/detail',$wish->item->id)); ?>">
					<?php if($wish->item->thumbnail != null): ?>
					<img src="<?php echo e(asset('images/movies/thumbnails/'.$wish->item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
					<?php endif; ?>
				</a>
			</div>
			<div id="prime-mix-description-block<?php echo e($wish->item->id); ?>" class="prime-description-block">
				<h5 class="description-heading"><?php echo e($wish->item->title); ?></h5>
				<div class="movie-rating">IMDB <?php echo e($wish->item->rating); ?></div>
				<ul class="description-list">
					<li><?php echo e($wish->item->duration); ?> <?php echo e($popover_translations->where('key', 'mins')->first->value->value); ?></li>
					<li><?php echo e($wish->item->publish_year); ?></li>
					<li><?php echo e($wish->item->maturity_rating); ?></li>
					<?php if($wish->item->subtitle == 1): ?>
					<li>CC</li>
					<li>
						<?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?>

					</li>
					<?php endif; ?>
				</ul>

				<?php if( $wish->item->detail ): ?>
				<div class="main-des">
					<p><?php echo e(str_limit($wish->item->detail, 150, '...')); ?></p>
				</div>
				<?php endif; ?>

				<div class="des-btn-block">
					<a onclick="playVideo(<?php echo e($wish->item->id); ?>,'<?php echo e($wish->item->type); ?>')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>
					
					<?php if($wish->item->trailer_url != null || $wish->item->trailer_url != ''): ?>
					<a onclick="playTrailer('<?php echo e($wish->item->trailer_url); ?>')" class="btn-default"><?php echo e($popover_translations->where('key', 'watch trailer')->first->value->value); ?></a>
					<?php endif; ?>

					<a onclick="addWish(<?php echo e($wish->item->id); ?>,'<?php echo e($wish->item->type); ?>')" class="addwishlistbtn<?php echo e($wish->item->id); ?><?php echo e($wish->item->type); ?> btn-default"><?php echo e($auth->addedWishlist($wish->item->id, $wish->item->type) ? $popover_translations->where('key', 'remove from watchlist')->first->value->value : $popover_translations->where('key', 'add to watchlist')->first->value->value); ?></a>
				</div>
			</div>
		</div>
		<?php endif; ?>
	<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>