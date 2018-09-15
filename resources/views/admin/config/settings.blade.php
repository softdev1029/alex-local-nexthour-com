@extends('layouts.admin')

@section('content')
  <div class="admin-form-main-block mrg-t-40">
    <h4 class="admin-form-text">General Settings</h4>
    @if ($config)
      {!! Form::model($config, ['method' => 'PATCH', 'action' => ['ConfigController@update', $config->id], 'files' => true]) !!}
        <div class="row admin-form-block z-depth-1">
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('w_name') ? ' has-error' : '' }}">
                {!! Form::label('w_name', 'Project Name') !!}
                <p class="inline info"> - Please enter your project name</p>
                {!! Form::text('w_name', null, ['class' => 'form-control']) !!}
                <small class="text-danger">{{ $errors->first('w_name') }}</small>
            </div>
            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
              {!! Form::label('title', 'Project Title') !!}
                <p class="inline info"> - Please enter your project title</p>              
              {!! Form::text('title', null, ['class' => 'form-control']) !!}
              <small class="text-danger">{{ $errors->first('title') }}</small>
            </div>
            <div class="form-group{{ $errors->has('w_email') ? ' has-error' : '' }}">
                {!! Form::label('w_email', 'Default Email') !!}
                <p class="inline info"> - Please enter your default email</p>
                {!! Form::email('w_email', null, ['class' => 'form-control', 'placeholder' => 'eg: foo@bar.com']) !!}
                <small class="text-danger">{{ $errors->first('w_email') }}</small>
            </div>
            {{-- <div class="form-group{{ $errors->has('stripe_pub_key') ? ' has-error' : '' }}">
                {!! Form::label('stripe_pub_key', 'Stripe publishable Key') !!}
                {!! Form::text('stripe_pub_key', null, ['class' => 'form-control']) !!}
                <small class="text-danger">{{ $errors->first('stripe_pub_key') }}</small>
            </div>
            <div class="form-group{{ $errors->has('stripe_secret_key') ? ' has-error' : '' }}">
                {!! Form::label('stripe_secret_key', 'Stripe Secret Key') !!}
                {!! Form::text('stripe_secret_key', null, ['class' => 'form-control']) !!}
                <small class="text-danger">{{ $errors->first('stripe_secret_key') }}</small>
            </div>
            <div class="form-group{{ $errors->has('paypal_mar_email') ? ' has-error' : '' }}">
                {!! Form::label('paypal_mar_email', 'Paypal Merchant mail') !!}
                {!! Form::email('paypal_mar_email', null, ['class' => 'form-control', 'placeholder' => 'eg: foo@bar.com']) !!}
                <small class="text-danger">{{ $errors->first('paypal_mar_email') }}</small>
            </div> --}}
            <div class="form-group{{ $errors->has('currency_code') ? ' has-error' : '' }}">
              {!! Form::label('currency_code', 'Currency Code') !!}
              <p class="inline info"> - Please enter your curreny code</p>
              {!! Form::text('currency_code', null, ['class' => 'form-control']) !!}
              <small class="text-danger">{{ $errors->first('currency_code') }}</small>
            </div>
            <div class="form-group{{ $errors->has('currency_symbol') ? ' has-error' : '' }} currency-symbol-block">
              {!! Form::label('currency_symbol', 'Currency Symbol') !!}
              <p class="inline info"> - Please select your currency symbol</p>
                <div class="input-group">
                  {!! Form::text('currency_symbol', null, ['class' => 'form-control currency-icon-picker']) !!}
                  <span class="input-group-addon simple-input"><i class="glyphicon glyphicon-user"></i></span>
                </div>
              <small class="text-danger">{{ $errors->first('currency_symbol') }}</small>
            </div>
            <div class="form-group{{ $errors->has('invoice_add') ? ' has-error' : '' }}">
              {!! Form::label('invoice_add', 'Invoice Address') !!}
              <p class="inline info"> - Please enter your invoice address</p>
              {!! Form::text('invoice_add', null, ['class' => 'form-control']) !!}
              <small class="text-danger">{{ $errors->first('invoice_add') }}</small>
            </div>
             <div class="bootstrap-checkbox form-group{{ $errors->has('goto') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-7">
                  <h5 class="bootstrap-switch-label">Go to Top</h5>
                </div>
                <div class="col-md-5 pad-0">
                  <div class="make-switch">
                    {!! Form::checkbox('goto', 1, ($button->goto == 1 ? true : false), ['class' => 'bootswitch', "data-on-text"=>"On", "data-off-text"=>"OFF", "data-size"=>"small"]) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <small class="text-danger">{{ $errors->first('goto') }}</small>
              </div>
            </div>
            <div class="bootstrap-checkbox form-group{{ $errors->has('color') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-7">
                  <h5 class="bootstrap-switch-label">Color Schemes</h5>
                </div>
                <div class="col-md-5 pad-0">
                  <div class="make-switch">
                    {!! Form::checkbox('color', 1, ($button->color == 1 ? true : false), ['class' => 'bootswitch', "data-on-text"=>"Light", "data-off-text"=>"Dark", "data-size"=>"small"]) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <small class="text-danger">{{ $errors->first('color') }}</small>
              </div>
            </div>  
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }} input-file-block">
                  {!! Form::label('logo', 'Project Logo') !!} - <p class="inline info">Size: 200x63</p>
                  {!! Form::file('logo', ['class' => 'input-file', 'id'=>'logo']) !!}
                  <label for="logo" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Project Logo">
                    <i class="icon fa fa-check"></i>
                    <span class="js-fileName">Choose a File</span>
                  </label>
                  <p class="info">Choose a logo</p>
                  <small class="text-danger">{{ $errors->first('logo') }}</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="image-block">
                  <img src="{{asset('images/logo/' . $config->logo)}}" class="img-responsive" alt="">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('favicon') ? ' has-error' : '' }} input-file-block">
                  {!! Form::label('favicon', 'Project favicon') !!} - <p class="inline info">Size: 32x32</p>
                  {!! Form::file('favicon', ['class' => 'input-file', 'id'=>'favicon']) !!}
                  <label for="favicon" class="btn btn-danger js-labelFile" data-toggle="tooltip" data-original-title="Project Favicon">
                    <i class="icon fa fa-check"></i>
                    <span class="js-fileName">Choose a File</span>
                  </label>
                  <p class="info">Choose a favicon</p>
                  <small class="text-danger">{{ $errors->first('favicon') }}</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="image-block">
                  <img src="{{asset('images/favicon/' . $config->favicon)}}" class="img-responsive" alt="">
                </div>
              </div>
            </div>
           {{--  <div class="bootstrap-checkbox form-group{{ $errors->has('prime_main_slider') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-7">
                  <h5 class="bootstrap-switch-label">Main Slider Type</h5>
                </div>
                <div class="col-md-5 pad-0">
                  <div class="make-switch">
                    {!! Form::checkbox('prime_main_slider', 1, ($config->prime_main_slider == '1' ? true : false), ['class' => 'bootswitch', "data-on-text"=>"Default", "data-off-text"=>"", "data-size"=>"small"]) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <small class="text-danger">{{ $errors->first('prime_main_slider') }}</small>
              </div>
            </div> --}}
            <div class="bootstrap-checkbox form-group{{ $errors->has('prime_genre_slider') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-7">
                  <h5 class="bootstrap-switch-label">Genre Slider Type</h5>
                </div>
                <div class="col-md-5 pad-0">
                  <div class="make-switch">
                    {!! Form::checkbox('prime_genre_slider', 1, ($config->prime_genre_slider == '1' ? true : false), ['class' => 'bootswitch', "data-on-text"=>"Layout 1", "data-off-text"=>"Layout 2", "data-size"=>"small"]) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <small class="text-danger">{{ $errors->first('prime_genre_slider') }}</small>
              </div>
            </div>
            <div class="bootstrap-checkbox form-group{{ $errors->has('prime_movie_single') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-7">
                  <h5 class="bootstrap-switch-label">Movie Single Type</h5>
                </div>
                <div class="col-md-5 pad-0">
                  <div class="make-switch">
                    {!! Form::checkbox('prime_movie_single', 1, ($config->prime_movie_single == '1' ? true : false), ['class' => 'bootswitch', "data-on-text"=>"Layout 1", "data-off-text"=>"Layout 2", "data-size"=>"small"]) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <small class="text-danger">{{ $errors->first('prime_movie_single') }}</small>
              </div>
            </div>
            <div class="bootstrap-checkbox form-group{{ $errors->has('prime_footer') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-7">
                  <h5 class="bootstrap-switch-label">Footer Type</h5>
                </div>
                <div class="col-md-5 pad-0">
                  <div class="make-switch">
                    {!! Form::checkbox('prime_footer', 1, ($config->prime_footer == '1' ? true : false), ['class' => 'bootswitch', "data-on-text"=>"Layout 1", "data-off-text"=>"Layout 2", "data-size"=>"small"]) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <small class="text-danger">{{ $errors->first('prime_footer') }}</small>
              </div>
            </div>
            <div class="bootstrap-checkbox form-group{{ $errors->has('preloader') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-7">
                  <h5 class="bootstrap-switch-label">Preloader</h5>
                </div>
                <div class="col-md-5 pad-0">
                  <div class="make-switch">
                    {!! Form::checkbox('preloader', 1, ($config->preloader == 1 ? true : false), ['class' => 'bootswitch', "data-on-text"=>"On", "data-off-text"=>"OFF", "data-size"=>"small"]) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <small class="text-danger">{{ $errors->first('preloader') }}</small>
              </div>
            </div>
             <div class="bootstrap-checkbox form-group{{ $errors->has('inspect') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-7">
                  <h5 class="bootstrap-switch-label">Inspect Disable</h5>
                </div>
                <div class="col-md-5 pad-0">
                  <div class="make-switch">
                    {!! Form::checkbox('inspect', 1, ($button->inspect == 1 ? true : false), ['class' => 'bootswitch', "data-on-text"=>"On", "data-off-text"=>"OFF", "data-size"=>"small"]) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <small class="text-danger">{{ $errors->first('inspect') }}</small>
              </div>
            </div>
            <div class="bootstrap-checkbox form-group{{ $errors->has('rightclick') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-7">
                  <h5 class="bootstrap-switch-label">Rightclick Disable</h5>
                </div>
                <div class="col-md-5 pad-0">
                  <div class="make-switch">
                    {!! Form::checkbox('rightclick', 1, ($button->rightclick == 1 ? true : false), ['class' => 'bootswitch', "data-on-text"=>"On", "data-off-text"=>"OFF", "data-size"=>"small"]) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <small class="text-danger">{{ $errors->first('rightclick') }}</small>
              </div>
            </div> 
          </div>
          <div class="btn-group col-xs-12">
            <button type="submit" class="btn btn-block btn-success">Save Settings</button>
          </div>
          <div class="clear-both"></div>
        </div>
      {!! Form::close() !!}
    @endif
  </div>
@endsection

