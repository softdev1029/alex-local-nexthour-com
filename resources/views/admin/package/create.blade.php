@extends('layouts.admin')
@section('content')
  <div class="admin-form-main-block mrg-t-40">  
    <h4 class="admin-form-text"><a href="{{url('admin/packages')}}" data-toggle="tooltip" data-original-title="Go back" class="btn-floating"><i class="material-icons">reply</i></a> Create Package</h4>
    <div class="row">
      <div class="col-md-6">
        <div class="admin-form-block z-depth-1">
          {!! Form::open(['method' => 'POST', 'action' => 'PackageController@store']) !!}
            <div class="form-group{{ $errors->has('plan_id') ? ' has-error' : '' }}">
                {!! Form::label('plan_id', 'Your Plan Id (Stripe Plan Id)') !!}
                <p class="inline info"> - Please enter your unique plan id for stripe</p>
                {!! Form::text('plan_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('plan_id') }}</small>
            </div>
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('name', 'Plan Name') !!}
                <p class="inline info"> - Please enter your plan name</p>
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('name') }}</small>
            </div>
  			    {!! Form::hidden('currency', $currency_code) !!}
    
            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                {!! Form::label('amount', ' Your Plan Amount') !!}
                <p class="inline info"> - Please enter your plan amount</p>
                <div class="input-group">
                  <span class="input-group-addon simple-input"><i class="{{$currency_symbol}}"></i></span>
                  {!! Form::number('amount', null, ['class' => 'form-control', 'required' => 'required']) !!}  
                </div>
                <small class="text-danger">{{ $errors->first('amount') }}</small>
            </div>
           <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-6">
                    <div class="form-group{{ $errors->has('interval_count') ? ' has-error' : '' }}">
                        {!! Form::label('interval_count', ' Your Plan Interval Count') !!}
                        <p class="inline info"> - Please enter renewal interval</p>
                        {!! Form::number('interval_count', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        <small class="text-danger">{{ $errors->first('interval_count') }}</small>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group{{ $errors->has('interval') ? ' has-error' : '' }}">
                        {!! Form::label('interval', 'Interval') !!}
                        <p class="inline info"> - Please select renewal interval time</p>
                        {!! Form::select('interval', ['day'=>'Daily', 'week' => 'Weekly', 'month' => 'Monthly', 'year' => 'yearly'], ['month' => 'Monthly'], ['class' => 'form-control select2', 'required' => 'required']) !!}
                        <small class="text-danger">{{ $errors->first('interval') }}</small>
                     </div>
                </div> 
             </div>   
          </div>
            <div class="form-group{{ $errors->has('trial_period_days') ? ' has-error' : '' }}">
                {!! Form::label('trial_period_days', ' Your Plan Trail Period Days') !!}
                <p class="inline info"> - Please enter your plan free trial period days</p>
                {!! Form::number('trial_period_days', null, ['class' => 'form-control']) !!}
                <small class="text-danger">{{ $errors->first('trial_period_days') }}</small>
            </div>
            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                {!! Form::label('status', 'Status') !!}
                <p class="inline info"> - Please select status</p>
                {!! Form::select('status', array('0' => 'Inactive', '1' => 'Active'), null, ['class' => 'form-control select2', 'placeholde' => '']) !!}
                <small class="text-danger">{{ $errors->first('status') }}</small>
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
@endsection
