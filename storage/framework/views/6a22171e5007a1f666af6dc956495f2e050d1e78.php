<?php if( $item->type == 'S' ): ?>
<div class="genre-prime-slide item">
	<div class="genre-slide-image protip" data-pt-placement="outside" data-pt-title="#prime-mix-description-block<?php echo e($item->id); ?><?php echo e($item->type); ?>">
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
	<div id="prime-mix-description-block<?php echo e($item->id); ?><?php echo e($item->type); ?>" class="prime-description-block">
		<h5 class="description-heading"><?php echo e($item->tvseries->title); ?></h5>
		<div class="movie-rating">IMDB <?php echo e($item->tvseries->rating); ?></div>
		<ul class="description-list">
			<li>Season <?php echo e($item->season_no); ?></li>
			<li><?php echo e($item->publish_year); ?></li>
			<li><?php echo e($item->tvseries->age_req); ?></li>
			<?php if($item->subtitle == 1): ?>
			<li>CC</li>
			<li>
				<?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?>

			</li>
			<?php endif; ?>
		</ul>
		
		<?php if( $item->detail || $item->tvseries->detail ): ?>
		<div class="main-des">
			<?php if($item->detail): ?>
				<p><?php echo e(str_limit($item->detail, 150, '...')); ?></p>
			<?php else: ?>
				<p><?php echo e(str_limit($item->tvseries->detail, 150, '...')); ?></p>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<div class="des-btn-block">
			<a onclick="playEpisodes(<?php echo e($item->id); ?>)" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>

			<a onclick="addWish(<?php echo e($item->id); ?>,'<?php echo e($item->type); ?>')" class="addwishlistbtn<?php echo e($item->id); ?><?php echo e($item->type); ?> btn-default"><?php echo e($auth->addedWishlist($item->id, $item->type) ? $popover_translations->where('key', 'remove from watchlist')->first->value->value : $popover_translations->where('key', 'add to watchlist')->first->value->value); ?></a>
		</div>
	</div>
</div>
<?php elseif($item->type == 'M'): ?>
<div class="genre-prime-slide">
	<div class="genre-slide-image protip" data-pt-placement="outside" data-pt-title="#prime-mix-description-block<?php echo e($item->id); ?>">
		<a href="<?php echo e(url('movie/detail',$item->id)); ?>">
			<?php if($item->thumbnail != null): ?>
			<img src="<?php echo e(asset('images/movies/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
			<?php endif; ?>
		</a>
	</div>
	<div id="prime-mix-description-block<?php echo e($item->id); ?>" class="prime-description-block">
		<h5 class="description-heading"><?php echo e($item->title); ?></h5>
		<div class="movie-rating">IMDB <?php echo e($item->rating); ?></div>
		<ul class="description-list">
			<li><?php echo e($item->duration); ?> <?php echo e($popover_translations->where('key', 'mins')->first->value->value); ?></li>
			<li><?php echo e($item->publish_year); ?></li>
			<li><?php echo e($item->maturity_rating); ?></li>
			<?php if($item->subtitle == 1): ?>
			<li>CC</li>
			<li>
				<?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?>

			</li>
			<?php endif; ?>
		</ul>

		<?php if( $item->detail ): ?>
		<div class="main-des">
			<p><?php echo e(str_limit($item->detail, 150, '...')); ?></p>
		</div>
		<?php endif; ?>

		<div class="des-btn-block">
			<a onclick="playVideo(<?php echo e($item->id); ?>,'<?php echo e($item->type); ?>')" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>
			
			<?php if($item->trailer_url != null || $item->trailer_url != ''): ?>
			<a onclick="playTrailer('<?php echo e($item->trailer_url); ?>')" class="btn-default"><?php echo e($popover_translations->where('key', 'watch trailer')->first->value->value); ?></a>
			<?php endif; ?>

			<a onclick="addWish(<?php echo e($item->id); ?>,'<?php echo e($item->type); ?>')" class="addwishlistbtn<?php echo e($item->id); ?><?php echo e($item->type); ?> btn-default"><?php echo e($auth->addedWishlist($item->id, $item->type) ? $popover_translations->where('key', 'remove from watchlist')->first->value->value : $popover_translations->where('key', 'add to watchlist')->first->value->value); ?></a>
		</div>
	</div>
</div>
<?php endif; ?>