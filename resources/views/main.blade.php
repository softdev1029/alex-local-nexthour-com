@extends('layouts.theme')

@section('main-wrapper')
<!-- main wrapper -->
<section id="main-wrapper" class="main-wrapper home-page">

	@if (isset($blocks) && count($blocks) > 0)
	@foreach ($blocks as $block)
	<!-- home out section -->
	<div id="home-out-section-1" class="home-out-section" style="background-image: url('{{ asset('images/main-home/'.$block->image) }}')">
		<div class="overlay-bg {{$block->left == 1 ? 'gredient-overlay-left' : 'gredient-overlay-right'}} "></div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6 {{$block->left == 1 ? 'col-md-offset-6 col-md-6 text-right' : ''}}">
					<h2 class="section-heading">{{$block->heading}}</h2>
					<p class="section-dtl {{$block->left == 1 ? 'pad-lt-100' : ''}}">{{$block->detail}}</p>
					@if ($block->button == 1)
					@if ($block->button_link == 'login')
					<a href="{{url('login')}}" class="btn btn-prime">{{$block->button_text}}</a>
					@else  
					<a href="{{url('register')}}" class="btn btn-prime">{{$block->button_text}}</a>
					@endif
					@endif
				</div>
			</div>  
		</div>
	</div>
	<!-- end out section -->
	@endforeach
	@endif
	<!-- Pricing plan main block -->
	@if(isset($plans) && count($plans) > 0)
	<div class="purchase-plan-main-block main-home-section-plans">
		<div class="panel-setting-main-block">
			<div class="container">
				<div class="plan-block-dtl">
					<h3 class="plan-dtl-heading">Membership Plans</h3>
					<ul>
						<li>Select any of your preferred membership package &amp; make payment.
						</li>
						<li>You can cancel your subscription anytime later.
						</li>
					</ul>
				</div>
				<div class="snip1404 row">
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
							@auth
							<div class="plan-select"><a href="{{route('get_payment', $plan->id)}}" class="btn btn-prime">Subscribe</a></div>
							@else
							<div class="plan-select"><a href="{{route('register')}}">Register Now</a></div>
							@endauth
						</div>  
					</div>
					@endif
					@endforeach
				</div>
			</div>
		</div>
	</div>
	@endif
	<!-- end featured main block -->
	<!-- end out section -->
</section>

<!-- end main wrapper -->
@endsection