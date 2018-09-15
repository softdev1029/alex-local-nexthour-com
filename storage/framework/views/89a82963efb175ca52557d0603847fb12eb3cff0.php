<?php $__env->startSection('content'); ?>
  <div class="admin-form-main-block mrg-t-40">
    <h4 class="admin-form-text"><a href="<?php echo e(url('admin/home_slider')); ?>" data-toggle="tooltip" data-original-title="Go back" class="btn-floating"><i class="material-icons">reply</i></a> Add Slide</h4>
    <div class="row">
      <div class="col-md-6">
        <div class="admin-form-block z-depth-1">
          <?php echo Form::open(['method' => 'POST', 'action' => 'HomeSliderController@store', 'files' => true]); ?>

            <div class="bootstrap-checkbox slide-option-switch form-group<?php echo e($errors->has('prime_main_slider') ? ' has-error' : ''); ?>">
              <div class="row">
                <div class="col-md-7">
                  <h5 class="bootstrap-switch-label">Slide For</h5>
                </div>
                <div class="col-md-5 pad-0">
                  <div class="make-switch">
                    <?php echo Form::checkbox('', 1, 1, ['class' => 'bootswitch', 'id' => 'TheCheckBox', "data-on-text"=>"Movies", "data-off-text"=>"Tv Series", "data-size"=>"small"]); ?>

                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <small class="text-danger"><?php echo e($errors->first('prime_main_slider')); ?></small>
              </div>
            </div>
            <div id="movie_id_block" class="form-group<?php echo e($errors->has('movie_id') ? ' has-error' : ''); ?>">
              <?php echo Form::label('movie_id', 'Select Slide For Movie'); ?>

              <?php echo Form::select('movie_id', $movie_list, null, ['class' => 'form-control select2', 'placeholder' => '']); ?>

              <small class="text-danger"><?php echo e($errors->first('movie_id')); ?></small>
            </div>
            <div id="tv_series_id_block" class="form-group<?php echo e($errors->has('tv_series_id') ? ' has-error' : ''); ?>">
              <?php echo Form::label('tv_series_id', 'Select Slide For Tv Show'); ?>

              <?php echo Form::select('tv_series_id', $tv_series_list, null, ['class' => 'form-control select2', 'placeholder' => '']); ?>

              <small class="text-danger"><?php echo e($errors->first('tv_series_id')); ?></small>
            </div>
            <div class="form-group<?php echo e($errors->has('slide_image') ? ' has-error' : ''); ?> input-file-block">
              <?php echo Form::label('slide_image', 'Slide Image'); ?> - <p class="inline info">Help block text</p>
              <?php echo Form::file('slide_image', ['class' => 'input-file', 'id'=>'slide_image']); ?>

              <label for="slide_image" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="slide image">
                <i class="icon fa fa-check"></i>
                <span class="js-fileName">Choose a File</span>
              </label>
              <p class="info">Choose slide image</p>
              <small class="text-danger"><?php echo e($errors->first('slide_image')); ?></small>
            </div>
            <div class="form-group<?php echo e($errors->has('active') ? ' has-error' : ''); ?>">
              <div class="row">
                <div class="col-xs-3">
                  <?php echo Form::label('active', 'Active'); ?>

                </div>
                <div class="col-xs-5 pad-0">
                  <label class="switch">
                    <?php echo Form::checkbox('active', 1, 1, ['class' => 'checkbox-switch']); ?>

                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
              <div class="col-xs-12">
                <small class="text-danger"><?php echo e($errors->first('series')); ?></small>
              </div>
            </div>
            <div class="btn-group pull-right">
              <button type="reset" class="btn btn-info"><i class="material-icons left">toys</i> Reset</button>
              <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Add Slide</button>
            </div>
            <div class="clear-both"></div>
          <?php echo Form::close(); ?>

        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom-script'); ?>
  <script>
    $(document).ready(function(){

      $('#tv_series_id_block').hide();

      $('#TheCheckBox').on('switchChange.bootstrapSwitch', function (event, state) {

          if (state == true) {

            $('#tv_series_id_block').hide();
            $('#movie_id_block').show();

          } else if (state == false) {

            $('#tv_series_id_block').show();
            $('#movie_id_block').hide(); 

          };

      });
      
    });
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>