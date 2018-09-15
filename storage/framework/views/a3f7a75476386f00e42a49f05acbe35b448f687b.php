<?php $__env->startSection('content'); ?>
  <div class="content-main-block mrg-t-40">
    <div class="admin-create-btn-block">
      <a href="<?php echo e(route('movies.create')); ?>" class="btn btn-danger btn-md"><i class="material-icons left">add</i> Create Movie</a>
      <!-- Delete Modal -->
      <a type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#bulk_delete"><i class="material-icons left">delete</i> Delete Selected</a>   
      <?php if(Session::has('changed_language')): ?>
        <a href="<?php echo e(route('tmdb_movie_translate')); ?>" class="btn btn-danger btn-md"><i class="material-icons left">translate</i> Translate all to <?php echo e(Session::get('changed_language')); ?></a>   
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
              <?php echo Form::open(['method' => 'POST', 'action' => 'MovieController@bulk_delete', 'id' => 'bulk_delete_form']); ?>

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
            <th>Movie Title</th>
            <th>Rating</th>
            <th>By TMDB</th>
            <th>Featured</th>
            <th>Actions</th>
          </tr>
        </thead>
        <?php if($movies): ?>
          <tbody>
            <?php ($no = 1); ?>
            <?php $__currentLoopData = $movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td>
                  <div class="inline">
                    <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="<?php echo e($movie->id); ?>" id="checkbox<?php echo e($movie->id); ?>">
                    <label for="checkbox<?php echo e($movie->id); ?>" class="material-checkbox"></label>
                  </div>
                  <?php echo e($no); ?>

                  <?php ($no++); ?>
                </td>
                <td><img
                  <?php if($movie->thumbnail): ?>
                    src="<?php echo e(asset('/images/movies/thumbnails/' . $movie->thumbnail)); ?>"
                  <?php elseif($movie->poster): ?>
                    src="<?php echo e(asset('/images/movies/posters/' . $movie->poster)); ?>"
                  <?php else: ?>
                    src="http://via.placeholder.com/70x70"
                  <?php endif; ?>
                    alt="Pic" width="70px" class="img-responsive">
                </td>
                <td><?php echo e($movie->title); ?></td>
                <td>
                    IMDB <?php echo e($movie->rating); ?>

                </td>
                <td>
                  <?php if($movie->tmdb == 'Y'): ?>
                    <i class="material-icons done">done</i>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td><?php echo $movie->featured == 1 ? '<i class="material-icons done">done</i>' : '-'; ?></td>
                <td>
                  <div class="admin-table-action-block"> 
                    <a href="<?php echo e(url('movie/detail', $movie->id)); ?>" data-toggle="tooltip" data-original-title="Page Preview" target="_blank" class="btn-default btn-floating"><i class="material-icons">desktop_mac</i></a>
                    <a href="<?php echo e(route('movies.edit', $movie->id)); ?>" data-toggle="tooltip" data-original-title="Edit" class="btn-info btn-floating"><i class="material-icons">mode_edit</i></a>
                    <!-- Delete Modal -->
                    <button type="button" class="btn-danger btn-floating" data-toggle="modal" data-target="#<?php echo e($movie->id); ?>deleteModal"><i class="material-icons">delete</i> </button>
                  </div>
                </td>
              </tr>
              <!-- Modal -->
                <div id="<?php echo e($movie->id); ?>deleteModal" class="delete-modal modal fade" role="dialog">
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
                        <?php echo Form::open(['method' => 'DELETE', 'action' => ['MovieController@destroy', $movie->id]]); ?>

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