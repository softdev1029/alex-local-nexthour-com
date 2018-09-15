<div class="genre-prime-slide">
	<div class="genre-slide-image protip" data-pt-placement="outside" data-pt-title="#section_<?php echo e($section); ?><?php echo e($item->id); ?><?php echo e($item->type); ?>">
		<a href="<?php echo e(url('movie/detail',$item->id)); ?>">
			<?php if($item->thumbnail != null): ?>
			<img src="<?php echo e(asset('images/movies/thumbnails/'.$item->thumbnail)); ?>" class="img-responsive" alt="genre-image">
			<?php endif; ?>
		</a>
	</div>
	<div id="section_<?php echo e($section); ?><?php echo e($item->id); ?><?php echo e($item->type); ?>" class="prime-description-block">
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