<?php $__env->startSection('main-wrapper'); ?>
  <!-- main wrapper -->
  <section id="main-wrapper" class="main-wrapper home-page user-account-section">
    <div class="container-fluid">
      <h4 class="heading">Dashboard</h4>
      
      <div class="panel-setting-main-block">
        <div class="panel-setting">
          <div class="row">
            <div class="col-md-6">
              <h4 class="panel-setting-heading">Your Details</h4>
              <p>Change your Name, Email, Mobile Number, Password, and more.</p>
            </div>
            <div class="col-md-3">
              <p class="info">Your Email: <?php echo e($auth->email); ?></p>
            </div>
            <div class="col-md-3">
              <div class="panel-setting-btn-block text-right">
                <a href="<?php echo e(url('account/profile')); ?>" class="btn btn-setting">Edit Details</a>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-setting">
          <div class="row">
            <div class="col-md-6">
              <h4 class="panel-setting-heading">Your Membership</h4>
              <p>Want to Change your Membership.</p>
            </div>
            <div class="col-md-3">
              <?php if($current_subscription != null): ?>
                <p class="info">Current Subscription: <?php echo e(ucfirst($current_subscription->lines->data[0]->plan->name)); ?></p>
              <?php endif; ?>
            </div>
            <div class="col-md-3">
              <div class="panel-setting-btn-block text-right">
                <?php
                  $subscribed = null;
                ?>
                <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php if($auth->subscribed($plan->plan_id)): ?>
                    <?php
                      $subscribed = 1;
                    ?>
                    <?php if($auth->subscription($plan->plan_id)->cancelled()): ?>
                      <a href="<?php echo e(route('resumeSub', $plan->plan_id)); ?>" class="btn btn-setting"><i class="fa fa-edit"></i>Resume Subscription</a>
                    <?php else: ?>
                      <a href="<?php echo e(route('cancelSub', $plan->plan_id)); ?>" class="btn btn-setting"><i class="fa fa-edit"></i>Cancel Subscription</a>
                    <?php endif; ?>
                  <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(isset($auth->paypal_subscriptions) && count($auth->paypal_subscriptions) > 0): ?>
                  <?php
                    $subscribed = 1;
                  ?>
                  <?php
                    $last = $auth->paypal_subscriptions->last();
                  ?>
                  <?php if(isset($last) && $last->status == 0): ?>
                    <a href="<?php echo e(route('resumeSubPaypal')); ?>" class="btn btn-setting"><i class="fa fa-edit"></i>Resume Subscription</a>
                  <?php elseif(isset($last) && $last->status == 1): ?>
                    <a href="<?php echo e(route('cancelSubPaypal')); ?>" class="btn btn-setting"><i class="fa fa-edit"></i>Cancel Subscription</a>
                  <?php endif; ?>
                <?php endif; ?>
                <?php if($subscribed == null): ?>
                  <a href="<?php echo e(url('account/purchaseplan')); ?>" class="btn btn-setting">Subscribe Now</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-setting">
          <div class="row">
            <div class="col-md-6">
              <h4 class="panel-setting-heading">Your Payment History</h4>
              <p>View your payment history.</p>
            </div>
            <div class="col-md-offset-3 col-md-3">
              <div class="panel-setting-btn-block text-right">
                <a href="<?php echo e(url('account/billing_history')); ?>" class="btn btn-setting">View Details</a>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </section>
  <!-- end main wrapper -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.theme', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>