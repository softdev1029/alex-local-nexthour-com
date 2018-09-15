@extends('layouts.theme')

@section('main-wrapper')
  <!-- main wrapper -->
  <section id="main-wrapper" class="main-wrapper home-page user-account-section">
    <div class="container-fluid">
      <h4 class="heading">Pricing Plan</h4>
      <ul class="bradcump">
        <li><a href="{{url('account')}}">Dashboard</a></li>
        <li>/</li>
        <li>Pricing Plan</li>
      </ul>
      <div class="purchase-plan-main-block main-home-section-plans">
        <div class="panel-setting-main-block">
          <div class="container">
            <div class="plan-block-dtl">
              <h3 class="plan-dtl-heading">Purchase Membership</h3>
              <h4 class="plan-dtl-sub-heading">Purchase any of the membership package from below.</h4>
              <ul>
                <li>Select any of your preferred membership package &amp; make payment.
                </li>
                <li>You can cancel your subscription anytime later.
                </li>
              </ul>
            </div>
            <div class="snip1404 row">
              @if(isset($plans))
                @foreach($plans as $plan)
                  @if($plan->status == 1)
                    <div class="col-md-4">
                      <div class="main-plan-section">
                        <header>
                          <h4 class="plan-title">
                            {{$plan->name}}
                          </h4>
                          <div class="plan-cost"><span class="plan-price"><i class="{{$currency_symbol}}"></i>{{$plan->amount}}</span><span class="plan-type">
                              @if($plan->interval == 'year')
                                Yearly
                              @elseif($plan->interval == 'month')
                                Monthly
                              @elseif($plan->interval == 'week')
                                Weekly
                              @elseif($plan->interval == 'day')
                                Daily
                              @endif
                          </span></div>
                        </header>
                        <ul class="plan-features">
                          <li><i class="fa fa-check"> </i>Min duration {{$plan->interval_count}} {{$plan->interval}}</li>
                          <li><i class="fa fa-check"> </i>Watch on your laptop, TV, phone and tablet</li>
                          <li><i class="fa fa-check"> </i>HD available</li>
                          <li><i class="fa fa-check"> </i>Unlimited movies and TV shows</li>
                          <li><i class="fa fa-check"> </i>24/7 Tech Support</li>
                          <li><i class="fa fa-check"> </i>Cancel anytime</li>
                        </ul>
                        <div class="plan-select"><a href="{{route('get_payment', $plan->id)}}" class="btn btn-prime">Subscribe</a></div>
                      </div>  
                    </div>
                  @endif
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end main wrapper -->
@endsection