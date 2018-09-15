@extends('layouts.admin')
@section('content')
  <div class="admin-form-main-block mrg-t-40">
    <h4 class="admin-form-text">SEO</h4>
    <div class="row admin-form-block z-depth-1">
      @if ($seo)
         {!! Form::model($seo, ['method' => 'PATCH', 'action' => ['SeoController@update', $seo->id], 'files' => true]) !!}
        
          <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
              {!! Form::label('description', 'Metadata Description ') !!}
              {!! Form::textarea('description', null, ['id' => 'textbox', 'class' => 'form-control']) !!}
              <small class="text-danger">{{ $errors->first('description') }}</small>
           </div>
          <div class="form-group{{ $errors->has('keyword') ? ' has-error' : '' }}">
              {!! Form::label('keyword', 'Metadata keyword ') !!}
              {!! Form::textarea('keyword', null, ['id' => 'textbox', 'class' => 'form-control']) !!}
              <small class="text-danger">{{ $errors->first('keyword') }}</small>
          </div>
    
          <div class="form-group{{ $errors->has('google') ? ' has-error' : '' }}">
                  {!! Form::label('google', 'Google Analytics (only id)') !!}
                  {!! Form::text('google', null, ['class' => 'form-control']) !!}
                  <small class="text-danger">{{ $errors->first('google') }}</small>
              </div>
          <div class="form-group{{ $errors->has('fb') ? ' has-error' : '' }}">
              {!! Form::label('fb', 'Facebook Pixcal(only id)') !!}
              {!! Form::text('fb', null, ['id' => 'textbox1', 'class' => 'form-control']) !!}
              <small class="text-danger">{{ $errors->first('fb') }}</small>
          </div>
          <div class="btn-group pull-right">
            <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Save</button>
          </div>
          <div class="clear-both"></div>
        {!! Form::close() !!}
      @endif
  </div>
 </div> 
@endsection

@section('custom-script')
  <script>
    $(function () {
      CKEDITOR.replace('editor1');
    });
  </script>
@endsection