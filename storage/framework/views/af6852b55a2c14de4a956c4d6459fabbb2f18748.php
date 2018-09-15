<?php $__env->startSection('main-wrapper'); ?>
  <!-- main wrapper -->
  <section id="main-wrapper" class="main-wrapper home-page user-account-section">
    <div class="container-fluid">
      <h4 class="heading">Account &amp; Settings</h4>
      <ul class="bradcump">
        <li><a href="<?php echo e(url('account')); ?>">Dashboard</a></li>
        <li>/</li>
        <li>Account &amp; Settings</li>
      </ul>
      <div class="panel-setting-main-block">
        <div class="edit-profile-main-block">
          <div class="row">
            <div class="col-md-6">
              <div class="edit-profile-block">
                <h4 class="panel-setting-heading">Change Email</h4>
                <div class="info">Current Email: <?php echo e(auth()->user()->email); ?></div>
                <?php echo Form::open(['method' => 'POST', 'action' => 'UserAccountController@update_profile']); ?>

                  <div class="form-group<?php echo e($errors->has('new_email') ? ' has-error' : ''); ?>">
                    <?php echo Form::label('new_email', 'New Email'); ?>

                    <?php echo Form::text('new_email', null, ['class' => 'form-control']); ?>

                    <small class="text-danger"><?php echo e($errors->first('new_email')); ?></small>
                  </div>
                  <div class="form-group<?php echo e($errors->has('current_password') ? ' has-error' : ''); ?>">
                    <?php echo Form::label('current_password', 'Current Password'); ?>

                    <?php echo Form::password('current_password', ['class' => 'form-control']); ?>

                    <small class="text-danger"><?php echo e($errors->first('current_password')); ?></small>
                  </div>
                  <div class="btn-group pull-right">
                    <?php echo Form::submit("Update", ['class' => 'btn btn-success']); ?>

                  </div>
                <?php echo Form::close(); ?>

              </div>
            </div>
            <div class="col-md-6">
              <div class="edit-profile-block">
                <h4 class="panel-setting-heading">Change Password</h4>
                <div class="info">want to change your password ?</div>
                <?php echo Form::open(['method' => 'POST', 'action' => 'UserAccountController@update_profile']); ?>

                  <div class="form-group<?php echo e($errors->has('current_password') ? ' has-error' : ''); ?>">
                    <?php echo Form::label('current_password', 'Current Password'); ?>

                    <?php echo Form::password('current_password', ['class' => 'form-control']); ?>

                    <small class="text-danger"><?php echo e($errors->first('current_password')); ?></small>
                  </div>
                  <div class="form-group<?php echo e($errors->has('new_password') ? ' has-error' : ''); ?>">
                    <?php echo Form::label('new_password', 'New Password'); ?>

                    <?php echo Form::password('new_password', ['class' => 'form-control']); ?>

                    <small class="text-danger"><?php echo e($errors->first('new_password')); ?></small>
                  </div>
                  <div class="btn-group pull-right">
                    <?php echo Form::submit("Update", ['class' => 'btn btn-success']); ?>

                  </div>
                <?php echo Form::close(); ?>

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