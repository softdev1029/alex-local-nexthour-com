<?php $__currentLoopData = $auth->continueWatchings(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<?php if( $item && !($item_id == $item->id && $item_type == $item->type) ): ?>
		<?php if( $item->type == 'S' ): ?>
			<?php echo $__env->make('partials.season_item', ['item' => $item, 'section' => 'continue_watching'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<?php elseif($item->type == 'M'): ?>
			<?php echo $__env->make('partials.movie_item', ['item' => $item, 'section' => 'continue_watching'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>