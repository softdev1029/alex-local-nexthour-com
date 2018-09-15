<?php $__env->startSection('content'); ?>
  <div class="content-main-block mrg-t-40">
    <div class="admin-create-btn-block">
      <a href="<?php echo e(route('tvseries.create')); ?>" class="btn btn-danger btn-md"><i class="material-icons left">add</i> Create TvSeries</a>
      <!-- Delete Modal -->
      <a type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#bulk_delete"><i class="material-icons left">delete</i> Delete Selected</a>   
      <?php if(Session::has('changed_language')): ?>
        <a href="<?php echo e(route('tmdb_tv_translate')); ?>" class="btn btn-danger btn-md"><i class="material-icons left">translate</i> Translate all to <?php echo e(Session::get('changed_language')); ?></a>   
      <?php endif; ?>
      <!-- Modal -->
      <div id="bulk_delete" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
              <h4 class="modal-heading">Are You Sure ?</h4>
              <p>Do you really want to delete these records? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
              <?php echo Form::open(['method' => 'POST', 'action' => 'TvSeriesController@bulk_delete', 'id' => 'bulk_delete_form']); ?>

                <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
              <?php echo Form::close(); ?>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="content-block box-body">
      <table id="movies_table" class="table table-hover">
        <thead>
          <tr class="table-heading-row">
            <th>
              <div class="inline">
                <input id="checkboxAll" type="checkbox" class="filled-in" name="checked[]" value="all" id="checkboxAll">
                <label for="checkboxAll" class="material-checkbox"></label>
              </div>
              #
            </th>
            <th>Thumbnail</th>
            <th>Tv Series Title</th>
            <th>Rating</th>
            <th>By TMDB</th>
            <th>Featured</th>
            <th>Actions</th>
          </tr>
        </thead>
        <?php if($tv_serieses): ?>
          <tbody>
            <?php $__currentLoopData = $tv_serieses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tv_series): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td>
                  <div class="inline">
                    <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="<?php echo e($tv_series->id); ?>" id="checkbox<?php echo e($tv_series->id); ?>">
                    <label for="checkbox<?php echo e($tv_series->id); ?>" class="material-checkbox"></label>
                  </div>
                  <?php echo e($key+1); ?>

                </td>
                <td><img
                  <?php if($tv_series->thumbnail != null): ?>
                    src="<?php echo e(asset('/images/tvseries/thumbnails/' . $tv_series->thumbnail)); ?>"
                  <?php elseif(isset($tv_series->seasons[0]) && $tv_series->seasons[0]->thumbnail != null): ?>
                    src="<?php echo e(asset('/images/tvseries/thumbnails/' . $tv_series->seasons[0]->thumbnail)); ?>"  
                  <?php elseif($tv_series->poster != null): ?>
                    src="<?php echo e(asset('/images/tvseries/posters/' . $tv_series->poster)); ?>"
                  <?php else: ?>
                    src="http://via.placeholder.com/70x70"
                  <?php endif; ?>
                    alt="Pic" width="70px" class="img-responsive">
                </td>
                <td><?php echo e($tv_series->title); ?></td>
                <td>
                  IMDB <?php echo e($tv_series->rating); ?>

                </td>
                <td>
                  <?php if($tv_series->tmdb == 'Y'): ?>
                    <i class="material-icons done">done</i>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td>
                  <?php echo $tv_series->featured == 1 ? '<i class="material-icons done">done</i>' : '-'; ?>

                </td>
                <td>
                  <div class="admin-table-action-block"> 
                    <?php if(count($tv_series->seasons) > 0): ?>
                      <a href="<?php echo e(url('show/detail', (isset($tv_series->seasons[0]) ? $tv_series->seasons[0]->id : ''))); ?>" data-toggle="tooltip" data-original-title="Page Preview" target="_blank" class="btn-default btn-floating"><i class="material-icons">desktop_mac</i></a>
                    <?php else: ?>
                      <a href="#" data-toggle="tooltip" data-original-title="Please add seasons from manage seasons" class="btn-default btn-floating disabled"><i class="material-icons">desktop_mac</i></a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('tvseries.edit', $tv_series->id)); ?>" data-toggle="tooltip" data-original-title="Edit" class="btn-info btn-floating"><i class="material-icons">mode_edit</i></a>
                    <a href="<?php echo e(route('tvseries.show', $tv_series->id)); ?>" data-toggle="tooltip" data-original-title="Manage Seasons" class="btn-success btn-floating"><i class="material-icons">settings</i></a>
                    <button type="button" class="btn-danger btn-floating" data-toggle="modal" data-target="#<?php echo e($tv_series->id); ?>deleteModal"><i class="material-icons">delete</i> </button>
                  </div>
                </td>
              </tr>
              <!-- Delete Modal -->
                <div id="<?php echo e($tv_series->id); ?>deleteModal" class="delete-modal modal fade" role="dialog">
                  <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="delete-icon"></div>
                      </div>
                      <div class="modal-body text-center">
                        <h4 class="modal-heading">Are You Sure ?</h4>
                        <p>Do you really want to delete these records? This process cannot be undone.</p>
                      </div>
                      <div class="modal-footer">
                        <?php echo Form::open(['method' => 'DELETE', 'action' => ['TvSeriesController@destroy', $tv_series->id]]); ?>

                            <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-danger">Yes</button>
                        <?php echo Form::close(); ?>

                      </div>
                    </div>
                  </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        <?php endif; ?>
      </table>
    </div>
  </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom-script'); ?>
  <script>
    $(function(){
      $('#checkboxAll').on('change', function(){
        if($(this).prop("checked") == true){
          $('.material-checkbox-input').attr('checked', true);
        }
        else if($(this).prop("checked") == false){
          $('.material-checkbox-input').attr('checked', false);
        }
      });
    });
  </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>