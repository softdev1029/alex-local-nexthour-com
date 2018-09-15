@extends('layouts.admin')
@section('content')
  <div class="admin-form-main-block">
    <h4 class="admin-form-text"><a href="{{url('admin/movies')}}" data-toggle="tooltip" data-original-title="Go back" class="btn-floating"><i class="material-icons">reply</i></a> Create Movie</h4>
    <div class="row">
      <div class="col-md-6">
        <div class="admin-form-block z-depth-1">
          {!! Form::open(['method' => 'POST', 'action' => 'MovieController@store', 'files' => true]) !!}
            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                {!! Form::label('title', 'Movie Title') !!}
                <p class="inline info"> - Please enter movie title</p>
                {!! Form::text('title', null, ['class' => 'form-control']) !!}
                <small class="text-danger">{{ $errors->first('title') }}</small>
            </div>
            <div class="pad_plus_border">
              <div class="bootstrap-checkbox slide-option-switch form-group{{ $errors->has('select_urls') ? ' has-error' : '' }}">
                <div class="row">
                  <div class="col-md-7">
                    <h5 class="bootstrap-switch-label">Select and Enter Urls</h5>
                  </div>
                  <div class="col-md-5 pad-0">
                    <div class="make-switch">
                      {!! Form::checkbox('ready_url_check', 1, 1, ['class' => 'bootswitch', 'id' => 'TheCheckBox', "data-on-text"=>"Ready Urls", "data-off-text"=>"Custom Url", "data-size"=>"small"]) !!}
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <small class="text-danger">{{ $errors->first('select_urls') }}</small>
                </div>
              </div>
              <div id="ready_url" class="form-group{{ $errors->has('ready_url') ? ' has-error' : '' }}">
                  {!! Form::label('ready_url', 'Youtube or Viemo Video Url') !!}
                  <p class="inline info"> - Please enter your video url</p>
                  {!! Form::text('ready_url', null, ['class' => 'form-control']) !!}
                  <small class="text-danger">{{ $errors->first('ready_url') }}</small>
              </div>
              <div id="custom_url">
                <p class="inline info">Openload, Google drive and other url add here!</p>
                <div class="form-group{{ $errors->has('url_360') ? ' has-error' : '' }}">
                    {!! Form::label('url_360', 'Video Url for 360 Quality') !!}
                    <p class="inline info"> - Please enter your video url</p>
                    {!! Form::text('url_360', null, ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('url_360') }}</small>
                </div>
                <div class="form-group{{ $errors->has('url_480') ? ' has-error' : '' }}">
                    {!! Form::label('url_480', 'Video Url for 480 Quality') !!}
                    <p class="inline info"> - Please enter your video url</p>
                    {!! Form::text('url_480', null, ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('url_480') }}</small>
                </div>
                <div class="form-group{{ $errors->has('url_720') ? ' has-error' : '' }}">
                    {!! Form::label('url_720', 'Video Url for 720 Quality') !!}
                    <p class="inline info"> - Please enter your video url</p>
                    {!! Form::text('url_720', null, ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('url_720') }}</small>
                </div>
                <div class="form-group{{ $errors->has('url_1080') ? ' has-error' : '' }}">
                    {!! Form::label('url_1080', 'Video Url for 1080 Quality') !!}
                    <p class="inline info"> - Please enter your video url</p>
                    {!! Form::text('url_1080', null, ['class' => 'form-control']) !!}
                    <small class="text-danger">{{ $errors->first('url_1080') }}</small>
                </div>
              </div>
            </div>
            <div class="form-group{{ $errors->has('a_language') ? ' has-error' : '' }}">
                {!! Form::label('a_language', 'Audio Languages') !!}
                <p class="inline info"> - Please select audio language</p>
                <div class="input-group">
                  {!! Form::select('a_language[]', $a_lans, null, ['class' => 'form-control select2', 'multiple']) !!}
                  <a href="#" data-toggle="modal" data-target="#AddLangModal" class="input-group-addon"><i class="material-icons left">add</i></a>
                </div>
                <small class="text-danger">{{ $errors->first('a_language') }}</small>
            </div>
            <div class="form-group{{ $errors->has('maturity_rating') ? ' has-error' : '' }}">
                {!! Form::label('maturity_rating', 'Maturity Rating') !!}
                <p class="inline info"> - Please select maturity rating</p>
                {!! Form::select('maturity_rating', array('all age' => 'All age', '13+' =>'13+', '16+' => '16+', '18+'=>'18+'), null, ['class' => 'form-control select2']) !!}
                <small class="text-danger">{{ $errors->first('maturity_rating') }}</small>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-xs-6">
                  {!! Form::label('', 'Choose custom thumbnail & poster') !!}
                </div>
                <div class="col-xs-5 pad-0">
                  <label class="switch for-custom-image">
                    {!! Form::checkbox('', 1, 0, ['class' => 'checkbox-switch']) !!}
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>
            <div class="upload-image-main-block">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('thumbnail') ? ' has-error' : '' }} input-file-block">
                    {!! Form::label('thumbnail', 'Thumbnail') !!} - <p class="info">Help block text</p>
                    {!! Form::file('thumbnail', ['class' => 'input-file', 'id'=>'thumbnail']) !!}
                    <label for="thumbnail" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Thumbnail">
                      <i class="icon fa fa-check"></i>
                      <span class="js-fileName">Choose a File</span>
                    </label>
                    <p class="info">Choose custom thumbnail</p>
                    <small class="text-danger">{{ $errors->first('thumbnail') }}</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('poster') ? ' has-error' : '' }} input-file-block">
                    {!! Form::label('poster', 'Poster') !!} - <p class="info">Help block text</p>
                    {!! Form::file('poster', ['class' => 'input-file', 'id'=>'poster']) !!}
                    <label for="poster" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Poster">
                      <i class="icon fa fa-check"></i>
                      <span class="js-fileName">Choose a File</span>
                    </label>
                    <p class="info">Choose custom poster</p>
                    <small class="text-danger">{{ $errors->first('poster') }}</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="pad_plus_border">
              <div class="form-group{{ $errors->has('subtitle') ? ' has-error' : '' }}">
    						<div class="row">
    							<div class="col-xs-6">
    								{!! Form::label('subtitle', 'Subtitle') !!}
    							</div>
    							<div class="col-xs-5 pad-0">
    								<label class="switch">
    									{!! Form::checkbox('subtitle', 1, 0, ['class' => 'checkbox-switch']) !!}
    									<span class="slider round"></span>
    								</label>
    							</div>
    						</div>
    						<div class="col-xs-12">
    							<small class="text-danger">{{ $errors->first('subtitle') }}</small>
    						</div>
              </div>
              <div class="form-group{{ $errors->has('subtitle_list') ? ' has-error' : '' }} subtitle_list">
                  {!! Form::label('subtitle_list', 'Subtitles List') !!}
                  <div class="input-group">
                    {!! Form::select('subtitle_list[]', $a_lans, null, ['class' => 'form-control select2', 'multiple']) !!}
                    <a href="#" data-toggle="modal" data-target="#AddLangModal" class="input-group-addon"><i class="material-icons left">add</i></a>
                  </div>
                  <small class="text-danger">{{ $errors->first('subtitle_list') }}</small>
              </div>
              {{-- <div id="subtitle-file" class="form-group{{ $errors->has('subtitle_files') ? ' has-error' : '' }} input-file-block">
                {!! Form::label('subtitle_files', 'Subtitle File') !!} - <p class="info">Help block text</p>
                {!! Form::file('subtitle_files', ['class' => 'input-file', 'id'=>'subtitle_files']) !!}
                <label for="subtitle_files" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Subtitle File">
                  <i class="icon fa fa-check"></i>
                  <span class="js-fileName">Choose a File</span>
                </label>
                <p class="info">Choose custom Subtitle File</p>
                <small class="text-danger">{{ $errors->first('subtitle_files') }}</small>
              </div> --}}
            </div>
            <div class="form-group{{ $errors->has('series') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-xs-6">
                  {!! Form::label('series', 'Series') !!}
                </div>
                <div class="col-xs-5 pad-0">
                  <label class="switch">
                    {!! Form::checkbox('series', 1, 0, ['class' => 'checkbox-switch']) !!}
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
              <div class="col-xs-12">
                <small class="text-danger">{{ $errors->first('series') }}</small>
              </div>
            </div>
            <div class="form-group{{ $errors->has('movie_id') ? ' has-error' : '' }} movie_id">
              {!! Form::label('movie_id', 'Select Movie Of Series') !!}
              {!! Form::select('movie_id', $movie_list_exc_series, null, ['class' => 'form-control select2']) !!}
              <small class="text-danger">{{ $errors->first('movie_id') }}</small>
            </div>
            <div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }}">
  						<div class="row">
  							<div class="col-xs-6">
  								{!! Form::label('featured', 'Featured') !!}
  							</div>
  							<div class="col-xs-5 pad-0">
  								<label class="switch">
  									{!! Form::checkbox('featured', 1, 0, ['class' => 'checkbox-switch']) !!}
  									<span class="slider round"></span>
  								</label>
  							</div>
  						</div>
  						<div class="col-xs-12">
  							<small class="text-danger">{{ $errors->first('featured') }}</small>
  						</div>
            </div>
            <div class="menu-block">
              <h6 class="menu-block-heading">Please Select Menu</h6>
              @if (isset($menus) && count($menus) > 0)
                <ul>
                  @foreach ($menus as $menu)
                    <li>
                      <div class="inline">
                        <input type="checkbox" class="filled-in material-checkbox-input" name="menu[]" value="{{$menu->id}}" id="checkbox{{$menu->id}}">
                        <label for="checkbox{{$menu->id}}" class="material-checkbox"></label>
                      </div>
                      {{$menu->name}}
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
            <div class="switch-field">
              <div class="switch-title">Want IMDB Ratings And More Or Custom?</div>
              <input type="radio" id="switch_left" class="imdb_btn" name="tmdb" value="Y" checked/>
              <label for="switch_left">TMDB</label>
              <input type="radio" id="switch_right" class="custom_btn" name="tmdb" value="N" />
              <label for="switch_right">Custom</label>
            </div>
            <div id="custom_dtl" class="custom-dtl">
              <div class="form-group{{ $errors->has('trailer_url') ? ' has-error' : '' }}">
                  {!! Form::label('trailer_url', 'Trailer Url') !!}
                  <p class="inline info"> - Please enter your trailer url</p>
                  {!! Form::text('trailer_url', null, ['class' => 'form-control']) !!}
                  <small class="text-danger">{{ $errors->first('trailer_url') }}</small>
              </div>
              <div class="form-group{{ $errors->has('director_id') ? ' has-error' : '' }}">
                  {!! Form::label('director_id', 'Directors') !!}
                  <p class="inline info"> - Please select your directors</p>
                  <div class="input-group">
                    {!! Form::select('director_id[]', $director_ls, null, ['class' => 'form-control select2', 'multiple']) !!}
                    <a href="#" data-toggle="modal" data-target="#AddDirectorModal" class="input-group-addon"><i class="material-icons left">add</i></a>
                  </div>
                  <small class="text-danger">{{ $errors->first('director_id') }}</small>
              </div>
              <div class="form-group{{ $errors->has('actor_id') ? ' has-error' : '' }}">
                  {!! Form::label('actor_id', 'Actors') !!}
                  <p class="inline info"> - Please select your actors</p>
                  <div class="input-group">
                    {!! Form::select('actor_id[]', $actor_ls, null, ['class' => 'form-control select2', 'multiple']) !!}
                    <a href="#" data-toggle="modal" data-target="#AddActorModal" class="input-group-addon"><i class="material-icons left">add</i></a>
                  </div>
                  <small class="text-danger">{{ $errors->first('actor_id') }}</small>
              </div>
              <div class="form-group{{ $errors->has('genre_id') ? ' has-error' : '' }}">
                  {!! Form::label('genre_id', 'Genre') !!}
                  <p class="inline info"> - Please select your genres</p>
                  <div class="input-group">
                    {!! Form::select('genre_id[]', $genre_ls, null, ['class' => 'form-control select2', 'multiple']) !!}
                    <a href="#" data-toggle="modal" data-target="#AddGenreModal" class="input-group-addon"><i class="material-icons left">add</i></a>
                  </div>
                  <small class="text-danger">{{ $errors->first('genre_id') }}</small>
              </div>
              <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                  {!! Form::label('duration', 'Duration') !!}
                  <p class="inline info"> - Please enter movie duration in (mins)</p>
                  {!! Form::text('duration', null, ['class' => 'form-control']) !!}
                  <small class="text-danger">{{ $errors->first('duration') }}</small>
              </div>
              <div class="form-group{{ $errors->has('publish_year') ? ' has-error' : '' }}">
                  {!! Form::label('publish_year', 'Publishing Year') !!}
                  <p class="inline info"> - Please enter movie publish year</p>
                  {!! Form::number('publish_year', null, ['class' => 'form-control', 'min' => '1900']) !!}
                  <small class="text-danger">{{ $errors->first('publish_year') }}</small>
              </div>
              <div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
                  {!! Form::label('rating', 'Ratings') !!}
                  <p class="inline info"> - Please enter ratings</p>
                  {!! Form::text('rating',  null, ['class' => 'form-control']) !!}
                  <small class="text-danger">{{ $errors->first('rating') }}</small>
              </div>
              <div class="form-group{{ $errors->has('released') ? ' has-error' : '' }}">
                  {!! Form::label('released', 'Released') !!}
                  <p class="inline info"> - Please enter movie released date</p>
                  {!! Form::date('released', null, ['class' => 'form-control']) !!}
                  <small class="text-danger">{{ $errors->first('released') }}</small>
              </div>
              <div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">
                  {!! Form::label('detail', 'Description') !!}
                  <p class="inline info"> - Please enter movie description</p>
                  {!! Form::textarea('detail', null, ['class' => 'form-control materialize-textarea', 'rows' => '5']) !!}
                  <small class="text-danger">{{ $errors->first('detail') }}</small>
              </div>
            </div>
            <div class="btn-group pull-right">
              <button type="reset" class="btn btn-info"><i class="material-icons left">toys</i> Reset</button>
              <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Create</button>
            </div>
            <div class="clear-both"></div>
          {!! Form::close() !!}
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
        {!! Form::open(['method' => 'POST', 'action' => 'AudioLanguageController@store']) !!}
        <div class="modal-body">
          <div class="form-group{{ $errors->has('language') ? ' has-error' : '' }}">
            {!! Form::label('language', 'Language') !!}
            {!! Form::text('language', null, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('language') }}</small>
          </div>
        </div>
        <div class="modal-footer">
          <div class="btn-group pull-right">
            <button type="reset" class="btn btn-info">Reset</button>
            <button type="submit" class="btn btn-success">Create</button>
          </div>
        </div>
        {!! Form::close() !!}
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
        {!! Form::open(['method' => 'POST', 'action' => 'DirectorController@store', 'files' => true]) !!}
          <div class="modal-body admin-form-block">          
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('name') }}</small>
            </div>
            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} input-file-block">
              {!! Form::label('image', 'Director Image') !!} - <p class="inline info">Help block text</p>
              {!! Form::file('image', ['class' => 'input-file', 'id'=>'image']) !!}
              <label for="image" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Director pic">
                <i class="icon fa fa-check"></i>
                <span class="js-fileName">Choose a File</span>
              </label>
              <p class="info">Choose custom image</p>
              <small class="text-danger">{{ $errors->first('image') }}</small>
            </div>
          </div>  
          <div class="modal-footer">            
            <div class="btn-group pull-right">
              <button type="reset" class="btn btn-info"><i class="material-icons left">toys</i> Reset</button>
              <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Create</button>
            </div>
            <div class="clear-both"></div>
          </div>  
        {!! Form::close() !!}
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
        {!! Form::open(['method' => 'POST', 'action' => 'ActorController@store', 'files' => true]) !!}
          <div class="modal-body admin-form-block">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('name') }}</small>
            </div>
            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} input-file-block">
              {!! Form::label('image', 'Director Image') !!} - <p class="inline info">Help block text</p>
              {!! Form::file('image', ['class' => 'input-file', 'id'=>'image']) !!}
              <label for="image" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Director pic">
                <i class="icon fa fa-check"></i>
                <span class="js-fileName">Choose a File</span>
              </label>
              <p class="info">Choose custom image</p>
              <small class="text-danger">{{ $errors->first('image') }}</small>
            </div>
          </div>
          <div class="modal-footer">
            <div class="btn-group pull-right">
              <button type="reset" class="btn btn-info"><i class="material-icons left">toys</i> Reset</button>
              <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Create</button>
            </div>
          </div>  
          <div class="clear-both"></div>
        {!! Form::close() !!}
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
        {!! Form::open(['method' => 'POST', 'action' => 'GenreController@store']) !!}
          <div class="modal-body admin-form-block">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('name') }}</small>
            </div>
          </div>
          <div class="modal-footer">
            <div class="btn-group pull-right">
              <button type="reset" class="btn btn-info"><i class="material-icons left">toys</i> Reset</button>
              <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Create</button>
            </div>
          </div>
          <div class="clear-both"></div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
@endsection

@section('custom-script')
	<script>
		$(document).ready(function(){
      $('form').on('submit', function(event){
        $('.loading-block').addClass('active');
      });
      $('#custom_url').hide();

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
			$('.subtitle_list').hide();
      $('#subtitle-file').hide();
			$('.movie_id').hide();
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
@endsection