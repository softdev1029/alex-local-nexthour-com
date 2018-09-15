<?php $__env->startSection('content'); ?>
  <div class="admin-form-main-block">
  	 <h4 class="admin-form-text"><a href="<?php echo e(url('admin/movies')); ?>" data-toggle="tooltip" data-original-title="Go back" class="btn-floating"><i class="material-icons">reply</i></a> Edit Movie</h4>
    <div class="row">
      <div class="col-md-6">
      	<div class="admin-form-block z-depth-1">
	        <?php echo Form::model($movie, ['method' => 'PATCH', 'action' => ['MovieController@update',$movie->id], 'files' => true]); ?>

						<div class="form-group<?php echo e($errors->has('title') ? ' has-error' : ''); ?>">
								<?php echo Form::label('title', 'Movie Title'); ?>

                <p class="inline info"> - Please enter movie title</p>
								<?php echo Form::text('title', null, ['class' => 'form-control']); ?>

								<small class="text-danger"><?php echo e($errors->first('title')); ?></small>
						</div>
						<div class="pad_plus_border">
              <div class="bootstrap-checkbox slide-option-switch form-group<?php echo e($errors->has('select_urls') ? ' has-error' : ''); ?>">
                <div class="row">
                  <div class="col-md-7">
                    <h5 class="bootstrap-switch-label">Select and Enter Urls</h5>
                  </div>
                  <div class="col-md-5 pad-0">
                    <div class="make-switch">
                      <?php echo Form::checkbox('ready_url_check', 1, ($movie->video_link->ready_url != null ? 1 : 0), ['class' => 'bootswitch', 'id' => 'TheCheckBox', "data-on-text"=>"Ready Urls", "data-off-text"=>"Custom Url", "data-size"=>"small"]); ?>

                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <small class="text-danger"><?php echo e($errors->first('select_urls')); ?></small>
                </div>
              </div>
              <div id="ready_url" class="form-group<?php echo e($errors->has('ready_url') ? ' has-error' : ''); ?>">
                  <?php echo Form::label('ready_url', 'Youtube or Viemo Video Url'); ?>

                  <p class="inline info"> - Please enter your video url</p>
                  <?php echo Form::text('ready_url', $movie->video_link->ready_url, ['class' => 'form-control']); ?>

                  <small class="text-danger"><?php echo e($errors->first('ready_url')); ?></small>
              </div>
              <div id="custom_url">
                <p class="inline info">Google drive and other url add here!</p>
                <div class="form-group<?php echo e($errors->has('url_360') ? ' has-error' : ''); ?>">
                    <?php echo Form::label('url_360', 'Video Url for 360 Quality'); ?>

                    <p class="inline info"> - Please enter your video url</p>
                    <?php echo Form::text('url_360', $movie->video_link->url_360, ['class' => 'form-control']); ?>

                    <small class="text-danger"><?php echo e($errors->first('url_360')); ?></small>
                </div>
                <div class="form-group<?php echo e($errors->has('url_480') ? ' has-error' : ''); ?>">
                    <?php echo Form::label('url_480', 'Video Url for 480 Quality'); ?>

                    <p class="inline info"> - Please enter your video url</p>
                    <?php echo Form::text('url_480', $movie->video_link->url_480, ['class' => 'form-control']); ?>

                    <small class="text-danger"><?php echo e($errors->first('url_480')); ?></small>
                </div>
                <div class="form-group<?php echo e($errors->has('url_720') ? ' has-error' : ''); ?>">
                    <?php echo Form::label('url_720', 'Video Url for 720 Quality'); ?>

                    <p class="inline info"> - Please enter your video url</p>
                    <?php echo Form::text('url_720', $movie->video_link->url_720, ['class' => 'form-control']); ?>

                    <small class="text-danger"><?php echo e($errors->first('url_720')); ?></small>
                </div>
                <div class="form-group<?php echo e($errors->has('url_1080') ? ' has-error' : ''); ?>">
                    <?php echo Form::label('url_1080', 'Video Url for 1080 Quality'); ?>

                    <p class="inline info"> - Please enter your video url</p>
                    <?php echo Form::text('url_1080', $movie->video_link->url_1080, ['class' => 'form-control']); ?>

                    <small class="text-danger"><?php echo e($errors->first('url_1080')); ?></small>
                </div>
              </div>
            </div>
						<div class="form-group<?php echo e($errors->has('a_language') ? ' has-error' : ''); ?>">
                <?php echo Form::label('a_language', 'Audio Languages'); ?>

                <p class="inline info"> - Please select audio language</p>
                <div class="input-group">
                  <select name="a_language[]" id="a_language" class="form-control select2" multiple="multiple">
                    <?php if(isset($old_lans) && count($old_lans) > 0): ?>
                      <?php $__currentLoopData = $old_lans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $old): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($old->id); ?>" selected="selected"><?php echo e($old->language); ?></option> 
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php if(isset($a_lans)): ?>
                      <?php $__currentLoopData = $a_lans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($rest->id); ?>"><?php echo e($rest->language); ?></option> 
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                  </select>  
                  <a href="#" data-toggle="modal" data-target="#AddLangModal" class="input-group-addon"><i class="material-icons left">add</i></a>
                </div>
                <small class="text-danger"><?php echo e($errors->first('a_language')); ?></small>
            </div>
            <div class="form-group<?php echo e($errors->has('maturity_rating') ? ' has-error' : ''); ?>">
                <?php echo Form::label('maturity_rating', 'Maturity Rating'); ?>

                <p class="inline info"> - Please select maturity rating</p>
                <?php echo Form::select('maturity_rating', array('all age' => 'All age', '13+' =>'13+', '16+' => '16+', '18+'=>'18+'), null, ['class' => 'form-control select2']); ?>

                <small class="text-danger"><?php echo e($errors->first('maturity_rating')); ?></small>
            </div>
						<div class="form-group">
              <div class="row">
                <div class="col-xs-6">
                  <?php echo Form::label('', 'Choose custom thumbnail & poster'); ?>

                </div>
                <div class="col-xs-5 pad-0">
                  <label class="switch for-custom-image">
                    <?php echo Form::checkbox('', 1, 0, ['class' => 'checkbox-switch']); ?>

                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>
						<div class="upload-image-main-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group<?php echo e($errors->has('thumbnail') ? ' has-error' : ''); ?> input-file-block">
                    <?php echo Form::label('thumbnail', 'Thumbnail'); ?> - <p class="info">Help block text</p>
                    <?php echo Form::file('thumbnail', ['class' => 'input-file', 'id'=>'thumbnail']); ?>

                    <label for="thumbnail" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Thumbnail">
                      <i class="icon fa fa-check"></i>
                      <span class="js-fileName">Choose a File</span>
                    </label>
                    <p class="info">Choose custom thumbnail</p>
                    <small class="text-danger"><?php echo e($errors->first('thumbnail')); ?></small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group<?php echo e($errors->has('poster') ? ' has-error' : ''); ?> input-file-block">
                    <?php echo Form::label('poster', 'Poster'); ?> - <p class="info">Help block text</p>
                    <?php echo Form::file('poster', ['class' => 'input-file', 'id'=>'poster']); ?>

                    <label for="poster" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Poster">
                      <i class="icon fa fa-check"></i>
                      <span class="js-fileName">Choose a File</span>
                    </label>
                    <p class="info">Choose custom poster</p>
                    <small class="text-danger"><?php echo e($errors->first('poster')); ?></small>
                  </div>
                </div>
              </div>
            </div>
            <div class="pad_plus_border">
              <div class="form-group<?php echo e($errors->has('subtitle') ? ' has-error' : ''); ?>">
                <div class="row">
                  <div class="col-xs-6">
                    <?php echo Form::label('subtitle', 'Subtitle'); ?>

                  </div>
                  <div class="col-xs-5 pad-0">
                    <label class="switch">
                      <?php echo Form::checkbox('subtitle', 1, $movie->subtitle, ['class' => 'checkbox-switch']); ?>

                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
                <div class="col-xs-12">
                  <small class="text-danger"><?php echo e($errors->first('subtitle')); ?></small>
                </div>
              </div>
              <div class="form-group<?php echo e($errors->has('subtitle_list') ? ' has-error' : ''); ?> subtitle_list">
                  <?php echo Form::label('subtitle_list', 'Subtitles List'); ?>

                  <div class="input-group">
                    <select name="subtitle_list[]" id="subtitle_list" class="form-control select2" multiple="multiple">
                      <?php if(isset($old_subtitles) && count($old_subtitles) > 0): ?>
                        <?php $__currentLoopData = $old_subtitles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $old): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($old->id); ?>" selected="selected"><?php echo e($old->language); ?></option> 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                      <?php if(isset($a_subs)): ?>
                        <?php $__currentLoopData = $a_subs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($rest->id); ?>"><?php echo e($rest->language); ?></option> 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>  
                    <a href="#" data-toggle="modal" data-target="#AddLangModal" class="input-group-addon"><i class="material-icons left">add</i></a>
                  </div>
                  <small class="text-danger"><?php echo e($errors->first('subtitle_list')); ?></small>
              </div>
              
            </div>
						<div class="form-group<?php echo e($errors->has('series') ? ' has-error' : ''); ?>">
              <div class="row">
                <div class="col-xs-6">
                  <?php echo Form::label('series', 'Series'); ?>

                </div>
                <div class="col-xs-5 pad-0">
                  <label class="switch">
                    <?php echo Form::checkbox('series', 1, $movie->series, ['class' => 'checkbox-switch']); ?>

                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
              <div class="col-xs-12">
                <small class="text-danger"><?php echo e($errors->first('series')); ?></small>
              </div>
            </div>
            <div class="form-group<?php echo e($errors->has('movie_id') ? ' has-error' : ''); ?> movie_id">
              <?php echo Form::label('movie_id', 'Select Movie Of Series'); ?>

							<?php echo Form::select('movie_id', [(isset($this_movie_series_detail) ? $this_movie_series_detail[0]->id : '')=>(isset($this_movie_series_detail) ? $this_movie_series_detail[0]->title : '')]+$movie_list_exc_series, null, ['class' => 'form-control select2']); ?>

              <small class="text-danger"><?php echo e($errors->first('movie_id')); ?></small>
            </div>
            <div class="form-group<?php echo e($errors->has('featured') ? ' has-error' : ''); ?>">
              <div class="row">
                <div class="col-xs-6">
                  <?php echo Form::label('featured', 'Featured'); ?>

                </div>
                <div class="col-xs-5 pad-0">
                  <label class="switch">                
                    <?php echo Form::checkbox('featured', 1, $movie->featured, ['class' => 'checkbox-switch']); ?>

                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
              <div class="col-xs-12">
                <small class="text-danger"><?php echo e($errors->first('featured')); ?></small>
              </div>
            </div>
            <div class="menu-block">
              <h6 class="menu-block-heading">Please Select Menu</h6>
              <?php if(isset($menus) && count($menus) > 0): ?>
                <ul>
                  <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                      <div class="inline">
                        <?php
                          $checked = null;
                          if (isset($menu->menu_data) && count($menu->menu_data) > 0) {
                            if ($menu->menu_data->where('movie_id', $movie->id)->where('menu_id', $menu->id)->first() != null) {
                              $checked = 1;
                            }
                          }
                        ?>
                        <?php if($checked == 1): ?>
                          <input type="checkbox" class="filled-in material-checkbox-input" name="menu[]" value="<?php echo e($menu->id); ?>" id="checkbox<?php echo e($menu->id); ?>" checked>
                          <label for="checkbox<?php echo e($menu->id); ?>" class="material-checkbox"></label>
                        <?php else: ?>
                          <input type="checkbox" class="filled-in material-checkbox-input" name="menu[]" value="<?php echo e($menu->id); ?>" id="checkbox<?php echo e($menu->id); ?>">
                          <label for="checkbox<?php echo e($menu->id); ?>" class="material-checkbox"></label>
                        <?php endif; ?>
                      </div>
                      <?php echo e($menu->name); ?>

                    </li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
              <?php endif; ?>
            </div>
						<div class="switch-field">
							<div class="switch-title">TMDB Data Or Custom data?</div>
							<input type="radio" id="switch_left" class="imdb_btn" name="tmdb" value="Y" <?php echo e($movie->tmdb == 'Y' ? 'checked' : ''); ?>/>
							<label for="switch_left">TMDB</label>
							<input type="radio" id="switch_right" class="custom_btn" name="tmdb" value="N" <?php echo e($movie->tmdb != 'Y' ? 'checked' : ''); ?>/>
							<label for="switch_right">Custom</label>
						</div>
						<div id="custom_dtl" class="custom-dtl">
              <div class="form-group<?php echo e($errors->has('trailer_url') ? ' has-error' : ''); ?>">
                  <?php echo Form::label('trailer_url', 'Trailer Url'); ?>

                  <p class="inline info"> - Please enter your trailer url</p>
                  <?php echo Form::text('trailer_url', null, ['class' => 'form-control']); ?>

                  <small class="text-danger"><?php echo e($errors->first('trailer_url')); ?></small>
              </div>
							<div class="form-group<?php echo e($errors->has('director_id') ? ' has-error' : ''); ?>">
								<?php echo Form::label('director_id', 'Directors'); ?>

                <p class="inline info"> - Please select your directors</p>
                <div class="input-group">
                  <select name="director_id[]" id="director_id" class="form-control select2" multiple="multiple">
                    <?php if(isset($old_director) && count($old_director) > 0): ?>
                      <?php $__currentLoopData = $old_director; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $old): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($old->id); ?>" selected="selected"><?php echo e($old->name); ?></option> 
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php if(isset($director_ls)): ?>
                      <?php $__currentLoopData = $director_ls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($rest->id); ?>"><?php echo e($rest->name); ?></option> 
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                  </select>  
                  <a href="#" data-toggle="modal" data-target="#AddDirectorModal" class="input-group-addon"><i class="material-icons left">add</i></a>
                </div>
								<small class="text-danger"><?php echo e($errors->first('director_id')); ?></small>
							</div>
							<div class="form-group<?php echo e($errors->has('actor_id') ? ' has-error' : ''); ?>">
									<?php echo Form::label('actor_id', 'Actors'); ?>

                  <p class="inline info"> - Please select your actors</p>
                  <div class="input-group">
                    <select name="actor_id[]" id="actor_id" class="form-control select2" multiple="multiple">
                      <?php if(isset($old_actor) && count($old_actor) > 0): ?>
                        <?php $__currentLoopData = $old_actor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $old): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($old->id); ?>" selected="selected"><?php echo e($old->name); ?></option> 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                      <?php if(isset($actor_ls)): ?>
                        <?php $__currentLoopData = $actor_ls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($rest->id); ?>"><?php echo e($rest->name); ?></option> 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>  
                    <a href="#" data-toggle="modal" data-target="#AddActorModal" class="input-group-addon"><i class="material-icons left">add</i></a>
                  </div>
									<small class="text-danger"><?php echo e($errors->first('actor_id')); ?></small>
							</div>
							<div class="form-group<?php echo e($errors->has('genre_id') ? ' has-error' : ''); ?>">
									<?php echo Form::label('genre_id', 'Genre'); ?>

                  <p class="inline info"> - Please select your genres</p>
                  <div class="input-group">
                    <select name="genre_id[]" id="genre_id" class="form-control select2" multiple="multiple">
                      <?php if(isset($old_genre) && count($old_genre) > 0): ?>
                        <?php $__currentLoopData = $old_genre; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $old): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($old->id); ?>" selected="selected"><?php echo e($old->name); ?></option> 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                      <?php if(isset($genre_ls)): ?>
                        <?php $__currentLoopData = $genre_ls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($rest->id); ?>"><?php echo e($rest->name); ?></option> 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>  
                    <a href="#" data-toggle="modal" data-target="#AddGenreModal" class="input-group-addon"><i class="material-icons left">add</i></a>
                  </div>
									<small class="text-danger"><?php echo e($errors->first('genre_id')); ?></small>
							</div>
							<div class="form-group<?php echo e($errors->has('duration') ? ' has-error' : ''); ?>">
									<?php echo Form::label('duration', 'Duration'); ?>

                  <p class="inline info"> - Please enter movie duration in (mins)</p>
									<?php echo Form::text('duration', null, ['class' => 'form-control']); ?>

									<small class="text-danger"><?php echo e($errors->first('duration')); ?></small>
							</div>
							<div class="form-group<?php echo e($errors->has('publish_year') ? ' has-error' : ''); ?>">
									<?php echo Form::label('publish_year', 'Publishing Year'); ?>

                  <p class="inline info"> - Please enter movie publish year</p>
									<?php echo Form::number('publish_year', null, ['class' =>   'form-control']); ?>

									<small class="text-danger"><?php echo e($errors->first('publish_year')); ?></small>
							</div>
							<div class="form-group<?php echo e($errors->has('rating') ? ' has-error' : ''); ?>">
									<?php echo Form::label('rating', 'Ratings'); ?>

                  <p class="inline info"> - Please enter ratings</p>
									<?php echo Form::text('rating', null, ['class' => 'form-control']); ?>

									<small class="text-danger"><?php echo e($errors->first('rating')); ?></small>
							</div>
							<div class="form-group<?php echo e($errors->has('released') ? ' has-error' : ''); ?>">
									<?php echo Form::label('released', 'Released'); ?>

                  <p class="inline info"> - Please enter movie released date</p>
									<?php echo Form::date('released', null, ['class' => 'form-control']); ?>

									<small class="text-danger"><?php echo e($errors->first('released')); ?></small>
							</div>
							<div class="form-group<?php echo e($errors->has('detail') ? ' has-error' : ''); ?>">
									<?php echo Form::label('detail', 'Description'); ?>

                  <p class="inline info"> - Please enter movie description</p>
									<?php echo Form::textarea('detail', null, ['class' => 'form-control materialize-textarea', 'rows' => '5']); ?>

									<small class="text-danger"><?php echo e($errors->first('detail')); ?></small>
							</div>
						</div>
						<div class="btn-group pull-right">
              <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Update</button>
						</div>
						<div class="clear-both"></div>
	        <?php echo Form::close(); ?>

	      </div>  
      </div>
    </div>
  </div>
  <!-- Add Language Modal -->
  <div id="AddLangModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title">Add Language</h5>
        </div>
        <?php echo Form::open(['method' => 'POST', 'action' => 'AudioLanguageController@store']); ?>

        <div class="modal-body">
          <div class="form-group<?php echo e($errors->has('language') ? ' has-error' : ''); ?>">
            <?php echo Form::label('language', 'Language'); ?>

            <?php echo Form::text('language', null, ['class' => 'form-control', 'required' => 'required']); ?>

            <small class="text-danger"><?php echo e($errors->first('language')); ?></small>
          </div>
        </div>
        <div class="modal-footer">
          <div class="btn-group pull-right">
            <button type="reset" class="btn btn-info">Reset</button>
            <button type="submit" class="btn btn-success">Create</button>
          </div>
        </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
  <!-- Add Director Modal -->
  <div id="AddDirectorModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title">Add Director</h5>
        </div>
        <?php echo Form::open(['method' => 'POST', 'action' => 'DirectorController@store', 'files' => true]); ?>

          <div class="modal-body admin-form-block">          
            <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                <?php echo Form::label('name', 'Name'); ?>

                <?php echo Form::text('name', null, ['class' => 'form-control', 'required' => 'required']); ?>

                <small class="text-danger"><?php echo e($errors->first('name')); ?></small>
            </div>
            <div class="form-group<?php echo e($errors->has('image') ? ' has-error' : ''); ?> input-file-block">
              <?php echo Form::label('image', 'Director Image'); ?> - <p class="inline info">Help block text</p>
              <?php echo Form::file('image', ['class' => 'input-file', 'id'=>'image']); ?>

              <label for="image" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Director pic">
                <i class="icon fa fa-check"></i>
                <span class="js-fileName">Choose a File</span>
              </label>
              <p class="info">Choose custom image</p>
              <small class="text-danger"><?php echo e($errors->first('image')); ?></small>
            </div>
          </div>  
          <div class="modal-footer">            
            <div class="btn-group pull-right">
              <button type="reset" class="btn btn-info"><i class="material-icons left">toys</i> Reset</button>
              <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Create</button>
            </div>
            <div class="clear-both"></div>
          </div>  
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
  <!-- Add Actor Modal -->
  <div id="AddActorModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title">Add Actor</h5>
        </div>
        <?php echo Form::open(['method' => 'POST', 'action' => 'ActorController@store', 'files' => true]); ?>

          <div class="modal-body admin-form-block">
            <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                <?php echo Form::label('name', 'Name'); ?>

                <?php echo Form::text('name', null, ['class' => 'form-control', 'required' => 'required']); ?>

                <small class="text-danger"><?php echo e($errors->first('name')); ?></small>
            </div>
            <div class="form-group<?php echo e($errors->has('image') ? ' has-error' : ''); ?> input-file-block">
              <?php echo Form::label('image', 'Director Image'); ?> - <p class="inline info">Help block text</p>
              <?php echo Form::file('image', ['class' => 'input-file', 'id'=>'image']); ?>

              <label for="image" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Director pic">
                <i class="icon fa fa-check"></i>
                <span class="js-fileName">Choose a File</span>
              </label>
              <p class="info">Choose custom image</p>
              <small class="text-danger"><?php echo e($errors->first('image')); ?></small>
            </div>
          </div>
          <div class="modal-footer">
            <div class="btn-group pull-right">
              <button type="reset" class="btn btn-info"><i class="material-icons left">toys</i> Reset</button>
              <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Create</button>
            </div>
          </div>  
          <div class="clear-both"></div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
  <!-- Add Genre Modal -->
  <div id="AddGenreModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title">Add Genre</h5>
        </div>
        <?php echo Form::open(['method' => 'POST', 'action' => 'GenreController@store']); ?>

          <div class="modal-body admin-form-block">
            <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                <?php echo Form::label('name', 'Name'); ?>

                <?php echo Form::text('name', null, ['class' => 'form-control', 'required' => 'required']); ?>

                <small class="text-danger"><?php echo e($errors->first('name')); ?></small>
            </div>
          </div>
          <div class="modal-footer">
            <div class="btn-group pull-right">
              <button type="reset" class="btn btn-info"><i class="material-icons left">toys</i> Reset</button>
              <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Create</button>
            </div>
          </div>
          <div class="clear-both"></div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom-script'); ?>
	<script>
		$(document).ready(function(){
      <?php if($movie->video_link->ready_url != null): ?>
        $('#ready_url').show();
        $('#custom_url').hide(); 
      <?php else: ?>
        $('#ready_url').hide();
        $('#custom_url').show();
      <?php endif; ?>
      $('#TheCheckBox').on('switchChange.bootstrapSwitch', function (event, state) {

          if (state == true) {

             $('#ready_url').show();
            $('#custom_url').hide(); 

          } else if (state == false) {

            $('#ready_url').hide();
            $('#custom_url').show();

          };

      });

      $('.upload-image-main-block').hide();
      <?php if($movie->tmdb == 'N'): ?>
        $('#custom_dtl').show();
      <?php endif; ?>
      <?php if($movie->subtitle == 0): ?>
        $('.subtitle_list').hide();
  			$('#subtitle-file').hide();
      <?php endif; ?> 
      <?php if($movie->series == 0): ?>
  			$('.movie_id').hide();
      <?php endif; ?>
			$('input[name="subtitle"]').click(function(){
					if($(this).prop("checked") == true){
							$('.subtitle_list').fadeIn();
              $('#subtitle-file').fadeIn();
					}
					else if($(this).prop("checked") == false){
						$('.subtitle_list').fadeOut();
            $('#subtitle-file').fadeOut();
					}
			});
      $('.for-custom-image input').click(function(){
        if($(this).prop("checked") == true){
          $('.upload-image-main-block').fadeIn();
        }
        else if($(this).prop("checked") == false){
          $('.upload-image-main-block').fadeOut();
        }
      });
			$('input[name="series"]').click(function(){
					if($(this).prop("checked") == true){
							$('.movie_id').fadeIn();
					}
					else if($(this).prop("checked") == false){
						$('.movie_id').fadeOut();
					}
			});
    });
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>