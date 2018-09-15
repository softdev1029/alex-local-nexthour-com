@extends('layouts.theme')

@section('main-wrapper')
  <!-- main wrapper -->
  <section id="main-wrapper" class="main-wrapper home-page user-account-section">
    <div class="container-fluid">
      <h4 class="heading">Dashboard</h4>
      
      <div class="panel-setting-main-block">
        <div class="panel-setting">
          <div class="row">
            <div class="col-md-6">
              <h4 class="panel-setting-heading">Your Details</h4>
              <p>Change your Name, Email, Mobile Number, Password, and more.</p>
            </div>
            <div class="col-md-3">
              <p class="info">Your Email: {{$auth->email}}</p>
            </div>
            <div class="col-md-3">
              <div class="panel-setting-btn-block text-right">
                <a href="{{url('account/profile')}}" class="btn btn-setting">Edit Details</a>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-setting">
          <div class="row">
            <div class="col-md-6">
              <h4 class="panel-setting-heading">Your Membership</h4>
              <p>Want to Change your Membership.</p>
            </div>
            <div class="col-md-3">
              @if($current_subscription != null)
                <p class="info">Current Subscription: {{ucfirst($current_subscription->lines->data[0]->plan->name)}}</p>
              @endif
            </div>
            <div class="col-md-3">
              <div class="panel-setting-btn-block text-right">
                @php
                  $subscribed = null;
                @endphp
                @foreach($plans as $plan)
                  @if($auth->subscribed($plan->plan_id))
                    @php
                      $subscribed = 1;
                    @endphp
                    @if($auth->subscription($plan->plan_id)->cancelled())
                      <a href="{{route('resumeSub', $plan->plan_id)}}" class="btn btn-setting"><i class="fa fa-edit"></i>Resume Subscription</a>
                    @else
                      <a href="{{route('cancelSub', $plan->plan_id)}}" class="btn btn-setting"><i class="fa fa-edit"></i>Cancel Subscription</a>
                    @endif
                  @endif
                @endforeach
                @if (isset($auth->paypal_subscriptions) && count($auth->paypal_subscriptions) > 0)
                  @php
                    $subscribed = 1;
                  @endphp
                  @php
                    $last = $auth->paypal_subscriptions->last();
                  @endphp
                  @if(isset($last) && $last->status == 0)
                    <a href="{{route('resumeSubPaypal')}}" class="btn btn-setting"><i class="fa fa-edit"></i>Resume Subscription</a>
                  @elseif (isset($last) && $last->status == 1)
                    <a href="{{route('cancelSubPaypal')}}" class="btn btn-setting"><i class="fa fa-edit"></i>Cancel Subscription</a>
                  @endif
                @endif
                @if($subscribed == null)
                  <a href="{{url('account/purchaseplan')}}" class="btn btn-setting">Subscribe Now</a>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="panel-setting">
          <div class="row">
            <div class="col-md-6">
              <h4 class="panel-setting-heading">Your Payment History</h4>
              <p>View your payment history.</p>
            </div>
            <div class="col-md-offset-3 col-md-3">
              <div class="panel-setting-btn-block text-right">
                <a href="{{url('account/billing_history')}}" class="btn btn-setting">View Details</a>
              </div>
            </div>
          </div>
        </div>
        {{-- <div class="panel-setting">
          <div class="row">
            <div class="col-md-6">
              <h4 class="panel-setting-heading">Parent Controll</h4>
              <p>Change your parent controll settings.</p>
            </div>
            <div class="col-md-offset-3 col-md-3">
              <div class="panel-setting-btn-block text-right">
                <a href="#" class="btn btn-setting"><i class="fa fa-edit"></i>Change Settings</a>
              </div>
            </div>
          </div>
        </div> --}}
      </div>
    </div>
  </section>
  <!-- end main wrapper -->
@endsection