<?php $__env->startSection('content'); ?>
  <div class="content-main-block mrg-t-40">
  	<h4 class="admin-form-text">Dashboard</h4>
    <div class="row">
    	<div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="<?php echo e(url('admin/users')); ?>" class="small-box z-depth-1 hoverable bg-aqua default-color">
          <div class="inner">
            <h3><?php echo e($users_count); ?></h3>
            <p>Total Users</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="<?php echo e(url('admin/movies')); ?>" class="small-box z-depth-1 hoverable bg-red danger-color">
          <div class="inner">
            <h3><?php echo e($movies_count); ?></h3>
            <p>Total Movies</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="<?php echo e(url('admin/tvseries')); ?>" class="small-box z-depth-1 hoverable bg-green success-color">
          <div class="inner">
            <h3><?php echo e($tvseries_count); ?></h3>
            <p>Total Tv Serieses</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="<?php echo e(url('admin/packages')); ?>" class="small-box z-depth-1 hoverable bg-yellow secondary-color">
          <div class="inner">
            <h3><?php echo e($package_count); ?></h3>
            <p>Total Packages</p>
          </div>
          <div class="icon">
            <i class="fa fa-files-o"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="<?php echo e(url('admin/coupons')); ?>" class="small-box z-depth-1 hoverable bg-green warning-color">
          <div class="inner">
            <h3><?php echo e($coupon_count); ?></h3>
            <p>Total Coupons</p>
          </div>
          <div class="icon">
            <i class="fa fa-bookmark-o"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="<?php echo e(url('admin/faqs')); ?>" class="small-box z-depth-1 hoverable bg-yellow pink darken-4">
          <div class="inner">
            <h3><?php echo e($faq_count); ?></h3>
            <p>Total Faqs</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="<?php echo e(url('admin/genres')); ?>" class="small-box z-depth-1 hoverable bg-aqua  grey darken-2">
          <div class="inner">
            <h3><?php echo e($genres_count); ?></h3>
            <p>Total Genres</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
        </a>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>