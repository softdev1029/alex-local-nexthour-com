<?php $__env->startSection('main-wrapper'); ?>
<!-- main wrapper -->
<section class="main-wrapper main-wrapper-single-movie-prime">
	<div class="background-main-poster-overlay">
	<?php if($season->poster != null): ?>
		<div class="background-main-poster col-md-offset-4 col-md-6" style="background-image: url('<?php echo e(asset('images/tvseries/posters/'.$season->poster)); ?>');">
	<?php elseif($season->tvseries->poster != null): ?>
		<div class="background-main-poster col-md-offset-4 col-md-6" style="background-image: url('<?php echo e(asset('images/tvseries/posters/'.$season->tvseries->poster)); ?>');">
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
						<h2 class="section-heading"><?php echo e($season->tvseries->title); ?></h2>
						<div class="imdb-ratings-block">
							<ul>
								<li><?php echo e($season->publish_year); ?></li>
								<li><?php echo e($season->season_no); ?> <?php echo e($popover_translations->where('key', 'season')->first->value->value); ?></li>
								<li><?php echo e($season->tvseries->age_req); ?></li>
								<li>IMDB <?php echo e($season->tvseries->rating); ?></li>
								<?php if( $season->subtitle && $sub_titles ): ?>
								<li>CC</li>
								<li><?php echo e($sub_titles); ?></li>
								<?php endif; ?>
							</ul>
						</div>
						<p>
							<?php if( $season->detail ): ?>
								<?php echo e(str_limit($season->detail, 150, '...')); ?>

							<?php else: ?>
								<?php echo e(str_limit($season->tvseries->detail, 150, '...')); ?>

							<?php endif; ?>
						</p>
					</div>
					<div class="screen-casting-dtl">
						<ul class="casting-headers">
							<li><?php echo e($home_translations->where('key', 'starring')->first->value->value); ?></li>
							<li><?php echo e($home_translations->where('key', 'genres')->first->value->value); ?></li>
							<li><?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?></li>
							<li><?php echo e($home_translations->where('key', 'audio languages')->first->value->value); ?></li>
						</ul>
						<ul class="casting-dtl">
							<li><?php echo $actors ?: '-'; ?></li>
							<li><?php echo $genreString ?: '-'; ?></li>
							<li>
							<?php if( $season->subtitle && $sub_titles ): ?>
								<?php echo e($sub_titles); ?>

							<?php else: ?>
								-
							<?php endif; ?>
							</li>
							<li><?php echo $audio_languages ?: '-'; ?></li>
						</ul>
					</div>
					<div class="screen-play-btn-block">
						<a onclick="playEpisodes(<?php echo e($season->id); ?>)" class="btn btn-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><?php echo e($popover_translations->where('key', 'play')->first->value->value); ?></span></a>
						<div id="wishlistelement" class="btn-group btn-block">
							<div>
								<a onclick="addWish(<?php echo e($season->id); ?>,'<?php echo e($season->type); ?>')" class="addwishlistbtn<?php echo e($season->id); ?><?php echo e($season->type); ?> btn-default"><?php echo e($auth->addedWishlist($season->id, $season->type) ? ($popover_translations->where('key', 'remove from watchlist')->first->value->value) : ($popover_translations->where('key', 'add to watchlist')->first->value->value)); ?></a>
							</div>
						</div>
					</div>		
				</div>
				<div class="col-md-4">
					<div class="poster-thumbnail-block">
						<?php if($season->thumbnail != null): ?>
						<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$season->thumbnail)); ?>" class="img-responsive" alt="genre-image">
						<?php elseif($season->tvseries->thumbnail != null): ?>
						<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$season->tvseries->thumbnail)); ?>" class="img-responsive" alt="genre-image">
						<?php else: ?>
						<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if( count($season->tvseries->seasons) > 1 ): ?>
	<div class="container-fluid movie-series-section search-section" style="z-index:10; margin-bottom:0 !important;">
		<div class="row">
			<div class="col-sm-3">
				<h5 class="movie-series-heading" style="padding:5px 0 0;"><?php echo e($home_translations->where('key', 'seasons')->first->value->value); ?> <?php echo e(count($season->tvseries->seasons)); ?></h5>
			</div>
			<div class="col-sm-9">
				<div class="pull-left" style="margin:10px 0 0;">
					<select id="selectSeason" class="selectpicker" data-style="btn-default">
					<?php $__currentLoopData = $season->tvseries->seasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ss): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($ss->id); ?>"<?php echo e($season->id == $ss->id ? ' selected' : ''); ?>><?php echo e($ss->detail ? $ss->detail : $popover_translations->where('key', 'season')->first->value->value . ' ' . ($key + 1)); ?></option>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<!-- episodes -->
	<?php if(count($season->episodes) > 0): ?>
	<div class="container-fluid movie-series-section search-section" style="padding-top:0; ">
		<div>
			<?php $__currentLoopData = $season->episodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $episode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="movie-series-block">
				<div class="row">
					<div class="col-sm-3">
						<div class="movie-series-img">
							<?php if($episode->seasons->thumbnail != null): ?>
							<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$episode->seasons->thumbnail)); ?>" class="img-responsive" alt="genre-image">
							<?php elseif($episode->seasons->tvseries->thumbnail != null): ?>
							<img src="<?php echo e(asset('images/tvseries/thumbnails/'.$episode->seasons->tvseries->thumbnail)); ?>" class="img-responsive" alt="genre-image">
							<?php else: ?>
							<img src="<?php echo e(asset('images/default-thumbnail.jpg')); ?>" class="img-responsive" alt="genre-image">  
							<?php endif; ?>
						</div>
					</div>
					<div class="col-sm-7 pad-0">
						<a onclick="playEpisodes(<?php echo e($episode->seasons_id); ?>, <?php echo e($key); ?>)" class="btn btn-play btn-sm-play"><span class="play-btn-icon"><i class="fa fa-play"></i></span> <span class="play-text"><h5 class="movie-series-heading movie-series-name"><?php echo e($key+1); ?>. <?php echo e($episode->title); ?></h5></span></a>
						<ul class="movie-series-des-list">
							<li><?php echo e($episode->duration); ?> <?php echo e($popover_translations->where('key', 'mins')->first->value->value); ?></li>
							<li><?php echo e($episode->released); ?></li>
							<li><?php echo e($episode->seasons->tvseries->maturity_rating); ?></li>
							<li>
								<?php if($episode->seasons->subtitle == 1): ?>
								<?php echo e($popover_translations->where('key', 'subtitles')->first->value->value); ?>

								<?php endif; ?>
							</li>
						</ul>
						<?php if( $episode->detail ): ?>
						<p>
							<?php echo e(str_limit($episode->detail, 150, '...')); ?>

						</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	</div>
	<?php endif; ?>
	<!-- end episodes -->

	<?php if( $auth->wishlist->count() ): ?>
	<div class="genre-prime-block">
		<div class="container-fluid">
			<h5 class="section-heading"><?php echo e($home_translations->where('key', 'customers also watched')->first->value->value); ?></h5>
			<div class="genre-prime-slider owl-carousel owl-theme">
				<?php echo $__env->make('partials.popup_slide_items', ['item_id' => $season->id, 'item_type' => $season->type], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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

<script type="text/javascript">
$(document).ready(function() {
	$('#selectSeason').on('change', function() {
		location.href = '<?php echo e(url("show/detail/")); ?>/' + $(this).val();
	});
});
</script>

<?php echo $__env->make('partials.script_play', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.theme', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>