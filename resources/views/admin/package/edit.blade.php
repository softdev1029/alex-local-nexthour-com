@extends('layouts.admin')

@section('content')
  <div class="admin-form-main-block mrg-t-40">
    <h4 class="admin-form-text"><a href="{{url('admin/packages')}}" data-toggle="tooltip" data-original-title="Go back" class="btn-floating"><i class="material-icons">reply</i></a> Edit Package</h4>
    <div class="row">
      <div class="col-md-6">
        <div class="admin-form-block z-depth-1">
          {!! Form::model($package, ['method' => 'PATCH', 'action' => ['PackageController@update', $package->id]]) !!}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('name', 'Plan Name') !!}
                <p class="inline info"> - Please enter your plan name</p>
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('name') }}</small>
            </div>
            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                {!! Form::label('status', 'Status') !!}
                <p class="inline info"> - Please select status</p>
                {!! Form::select('status', array('0' => 'Inactive', '1' => 'Active'), null, ['class' => 'form-control select2', 'placeholde' => '']) !!}
                <small class="text-danger">{{ $errors->first('status') }}</small>
            </div>
            <div class="btn-group pull-right">
              <button type="submit" class="btn btn-success"><i class="material-icons left">add_to_photos</i> Update</button>
            </div>
            <div class="clear-both"></div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection
