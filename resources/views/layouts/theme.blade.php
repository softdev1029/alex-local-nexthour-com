<!DOCTYPE html>
<!--
**********************************************************************************************************
    Copyright (c) 2018 .
    **********************************************************************************************************  -->
<!--
Template Name: Next Hour - Movie Tv Show & Video Subscription Portal Cms
Version: 1.0.0
Author: Media City
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]> -->
<html lang="en">
<!-- <![endif]-->
<!-- head -->
<head>
	<title>{{$w_title}}</title>
	<meta charset="utf-8" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta name="Description" content="{{$description}}" />
	<meta name="keyword" content="{{$w_title}}, {{$keyword}}">
	<meta name="MobileOptimized" content="320" />
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" type="image/icon" href="{{asset('images/favicon/favicon.png')}}"> <!-- favicon-icon -->
	<!-- theme style -->
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> <!-- google font -->
	<link href="{{asset('css/videojs-icons.css')}}" rel="stylesheet" type="text/css"/> <!-- google font -->
	<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/> <!-- bootstrap css -->

	@if ( Route::currentRouteName() == 'showSeason' )
	<link href="{{asset('css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
	@endif

	<link href="https://vjs.zencdn.net/6.6.0/video-js.css" rel="stylesheet"> <!-- videojs css -->
	<link href="{{asset('css/menumaker.css')}}" type="text/css" rel="stylesheet"> <!-- menu css -->
	<link href="{{asset('css/owl.carousel.min.css')}}" rel="stylesheet" type="text/css"/> <!-- owl carousel css -->
	<link href="{{asset('css/owl.theme.default.min.css')}}?v=1.2" rel="stylesheet" type="text/css"/> <!-- owl carousel css -->
	<link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/> <!-- fontawsome css -->
	<link href="{{asset('css/popover.css')}}" rel="stylesheet" type="text/css"/> <!-- bootstrap popover css -->
	<link href="{{asset('css/layers.css')}}" rel="stylesheet" type="text/css"/> <!-- Revolution css -->
	<link href="{{asset('css/navigation.css')}}" rel="stylesheet" type="text/css"/> <!-- Revolution css -->
	<link href="{{asset('css/pe-icon-7-stroke.css')}}" rel="stylesheet" type="text/css"/> <!-- Revolution css -->
	<link href="{{asset('css/settings.css')}}?v=1.5" rel="stylesheet" type="text/css"/> <!-- Revolution css -->
	
	<!-- <link href="{{asset('css/videojs-playlist-ui.vertical.css')}}" rel="stylesheet" type="text/css"/> -->

	@if($color==1) 
	<link href="{{asset('css/style-light.css')}}" rel="stylesheet" type="text/css"/> <!-- custom css -->
	@else
	<link href="{{asset('css/style.css')}}?v=1.3" rel="stylesheet" type="text/css"/> <!-- custom css -->
	@endif

	<link href="{{asset('css/goto.css')}}" rel="stylesheet" type="text/css"/><!-- Go to Top css -->

	@yield('stylesheets')

	<script src="https://js.stripe.com/v3/"></script>
	<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
	<script>
		window.Laravel =  <?php echo json_encode([
			'csrfToken' => csrf_token(),
			]); ?>
		</script>
		<script type="text/javascript" src="{{asset('js/app-dist.js')}}"></script> <!-- app library js -->
		<!-- end theme style -->

	</head>
	<!-- end head -->
	<!--body start-->
	<body>
		<!-- preloader -->
		@if ($preloader == 1)
		<div class="loading">
			<div class="logo">
				<img src="{{ asset('images/logo/'.$logo) }}" class="img-responsive" alt="{{$w_title}}">
			</div>
			<div class="loading-text">
				<span class="loading-text-words">L</span>
				<span class="loading-text-words">O</span>
				<span class="loading-text-words">A</span>
				<span class="loading-text-words">D</span>
				<span class="loading-text-words">I</span>
				<span class="loading-text-words">N</span>
				<span class="loading-text-words">G</span>
			</div>
		</div>
		@endif
		<!-- end preloader -->
		<div class="body-overlay-bg"></div>
		@if (Session::has('added'))
		<div id="sessionModal" class="sessionmodal rgba-green-strong z-depth-2">
			<i class="fa fa-check-circle"></i> <p>{{session('added')}}</p>
		</div>
		@elseif (Session::has('updated'))
		<div id="sessionModal" class="sessionmodal rgba-cyan-strong z-depth-2">
			<i class="fa fa-exclamation-triangle"></i> <p>{{session('updated')}}</p>
		</div>
		@elseif (Session::has('deleted'))
		<div id="sessionModal" class="sessionmodal rgba-red-strong z-depth-2">
			<i class="fa fa-window-close"></i> <p>{{session('deleted')}}</p>
		</div>
		@endif
		<!-- preloader -->
		<div class="preloader">
			<div class="status">
				<div class="status-message">
				</div>
			</div>
		</div>
		<!-- end preloader -->
		<!-- navigation -->
		<div class="navigation">
			<div class="container-fluid nav-container">
				<div class="row">
					<div class="col-sm-2">
						<div class="nav-logo">
							@if(isset($logo) != null)
							<a href="{{isset($nav_menus[0]) ? route('home', strtolower($nav_menus[0]->name)) : '#'}}" title="{{$w_title}}"><img src="{{asset('images/logo/'.$logo)}}" class="img-responsive" alt="{{$w_title}}"></a>
							@else
							<a href="{{route('home', $nav_menus[0]->name)}}" title="{{$w_title}}"><img src="images/logo.png" class="img-responsive" alt="{{$w_title}}"></a>
							@endif
						</div>
					</div>
					<div class="col-sm-4">
						@auth
						@php
						$subscribed = null;
						if (isset($auth)) {
						$auth = Illuminate\Support\Facades\Auth::user();
						if ($auth->is_admin == 1) {
						$subscribed = 1;
					} else if ($auth->stripe_id != null) {
					Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
					$plans = App\Package::all();
					foreach ($plans as $key => $plan) {
					if ($auth->subscribed($plan->plan_id)) {
					$subscribed = 1;
				}
			} 
		} else if (isset($auth->paypal_subscriptions)) {  
		//Check Paypal Subscription of user
		$last_payment = $auth->paypal_subscriptions->last();
		if (isset($last_payment) && $last_payment->status == 1) {
		//check last date to current date
		$current_date = Illuminate\Support\Carbon::now();
		if (date($current_date) <= date($last_payment->subscription_to)) {
		$subscribed = 1;
	}
}
}
}
@endphp
@if($subscribed == 1)
<div id="cssmenu">
	@if (isset($nav_menus) && count($nav_menus) > 0)
	<ul>
		@foreach ($nav_menus as $menu)
		<li><a class="{{ Nav::hasSegment($menu->slug) }}" href="{{url('/', $menu->slug)}}"  title="{{$menu->name}}">{{$menu->name}}</a></li>
		@endforeach
	</ul>
	@endif
</div>
@endif
@endauth
</div>
<div class="col-sm-6 pull-right">
	<div class="login-panel-main-block">
		<ul>
			@auth
			@if($subscribed == 1)
			<li class="prime-search-block">
				{!! Form::open(['method' => 'GET', 'action' => 'HomeController@search', 'class' => 'search_form']) !!}
				<div class="aa-input-container" id="aa-input-container">
					{!! Form::text('search', null, ['class' => 'search-input', 'placeholder' => 'Search','required']) !!}
					<button type="submit" class="search-button"><i class="fa fa-search"></i>
					</button>
				</div>
				{!! Form::close() !!}
			</li>
			@endif
			@if (isset($languages) && count($languages) > 0)
			<li class="sign-in-block language-switch-block">
				<div class="dropdown prime-dropdown">
					<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-globe"></i> {{Session::has('changed_language') ? ucfirst(Session::get('changed_language')) : ''}}</button>
					<span class="caret"></span></button>
					<ul class="dropdown-menu prime-dropdown-menu">
						@foreach ($languages as $language)
						<li><a href="{{ route('languageSwitch', $language->local) }}">{{$language->name}}</a></li>
						@endforeach
					</ul>
				</div>
			</li>
			@endif
			<li class="sign-in-block">
				<div class="dropdown prime-dropdown">
					<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-user-circle"></i> {{$auth ? $auth->name : ''}}
						<span class="caret"></span></button>
						<ul class="dropdown-menu prime-dropdown-menu">
							@if($auth->is_admin == 1)
							<li><a href="{{url('admin')}}" target="_blank">Admin {{$header_translations->where('key', 'dashboard') ? $header_translations->where('key', 'dashboard')->first->value->value : ''}}</a></li>
							@endif
							@if($subscribed == 1)
							<li><a href="{{url('account/watchlist/movies')}}" class="active">{{$header_translations->where('key', 'watchlist')->first->value->value}}</a></li>
							@else  
							<li><a href="{{url('account/purchaseplan')}}">Subscribe</a></li>
							@endif
							<li><a href="{{url('account')}}">{{$header_translations->where('key', 'dashboard') ? $header_translations->where('key', 'dashboard')->first->value->value : ''}}</a></li>
							<li><a href="{{url('faq')}}">{{$header_translations->where('key', 'faqs') ? $header_translations->where('key', 'faqs')->first->value->value : ''}}</a></li>
							<li>
								<a href="{{ route('logout') }}"
								onclick="event.preventDefault();
								document.getElementById('logout-form').submit();">
								{{$header_translations->where('key', 'sign out') ? $header_translations->where('key', 'sign out')->first->value->value : ''}}
							</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								{{ csrf_field() }}
							</form>
						</li>
					</ul>
				</div>
			</li>
			@else
			<li class="sign-in-block mrgn-rt-20"><a class="sign-in" href="{{url('login')}}"><i class="fa fa-sign-in"></i> {{$header_translations->where('key', 'sign in') ? $header_translations->where('key', 'sign in')->first->value->value : ''}}</a></li>
			<li class="sign-in-block"><a class="sign-in" href="{{url('register')}}"><i class="fa fa-user-plus"></i> {{$header_translations->where('key', 'register') ? $header_translations->where('key', 'register')->first->value->value : ''}}</a></li>  
			@endauth 
		</ul>
	</div>
</div>
</div>
</div>
</div>
<div>
	{{-- <video id="maat-player" class="video-js vjs-default-skin" controls>
		<source src="https://bitdash-a.akamaihd.net/content/sintel/hls/playlist.m3u8" type="application/x-mpegURL">
		</video>
		<div id="audioTrackChoice">
			<select id="enabled-audio-track" name="enabled-audio-track">
			</select>
		</div>
	</div> --}}
	<!-- end navigation -->
	@yield('main-wrapper')
	<!-- footer -->
	@if($prime_footer == 1)
	<footer id="prime-footer" class="prime-footer-main-block">
		<div class="container-fluid">
			<div style="height:0px;">
				<a id="back2Top" title="Back to top" href="#">&#10148;</a>
			</div>
			<div class="logo">
				<img src="{{asset('images/logo/'.$logo)}}" class="img-responsive" alt="{{$w_title}}">
			</div>
			<div class="copyright">
				<ul>
					<li>
						@if(isset($copyright))
						{!! $copyright !!}
						@endif
					</li>
				</ul>
				<ul>
					<li><a href="{{url('terms_condition')}}">{{$footer_translations->where('key', 'terms and condition') ? $footer_translations->where('key', 'terms and condition')->first->value->value : ''}}</a></li>
					<li><a href="{{url('privacy_policy')}}">{{$footer_translations->where('key', 'privacy policy') ? $footer_translations->where('key', 'privacy policy')->first->value->value : ''}}</a></li>
					<li><a href="{{url('refund_policy')}}">{{$footer_translations->where('key', 'refund policy') ? $footer_translations->where('key', 'refund policy')->first->value->value : ''}}</a></li>
					<li><a href="{{url('faq')}}">{{$footer_translations->where('key', 'help') ? $footer_translations->where('key', 'help')->first->value->value : ''}}</a></li>
				</ul>
			</div>
		</div>
	</footer>
	@else
	<footer id="footer-main-block" class="footer-main-block">
		<div class="pre-footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3">
						<div class="footer-logo footer-widgets">
							@if(isset($logo))
							<img src="{{asset('images/logo/'.$logo)}}" class="img-responsive" alt="{{$w_title}}">
							@endif
						</div>
					</div>
					<div class="col-md-4">
						<div class="footer-widgets">
							<div class="row">
								<div class="col-md-6">
									<div class="footer-links-block">
										<h4 class="footer-widgets-heading">{{$footer_translations->where('key', 'corporate') ? $footer_translations->where('key', 'corporate')->first->value->value : ''}}</h4>
										<ul>
											<li><a href="{{url('terms_condition')}}">{{$footer_translations->where('key', 'terms and condition') ? $footer_translations->where('key', 'terms and condition')->first->value->value : ''}}</a></li>
											<li><a href="{{url('privacy_policy')}}">{{$footer_translations->where('key', 'privacy policy') ? $footer_translations->where('key', 'privacy policy')->first->value->value : ''}}</a></li>
											<li><a href="{{url('refund_policy')}}">{{$footer_translations->where('key', 'refund policy') ? $footer_translations->where('key', 'refund policy')->first->value->value : ''}}</a></li>
											<li><a href="{{url('faq')}}">{{$footer_translations->where('key', 'help') ? $footer_translations->where('key', 'help')->first->value->value : ''}}</a></li>

										</ul>
									</div>
								</div>
								<div class="col-md-6">
									<div class="footer-links-block">
										<h4 class="footer-widgets-heading">{{$footer_translations->where('key', 'sitemap') ? $footer_translations->where('key', 'sitemap')->first->value->value : ''}}</h4>
										<ul>
											<li><a href="{{url('home')}}">Home</a></li>
											<li><a href="{{url('movies')}}">Movies</a></li>
											<li><a href="{{url('tvseries')}}">Tv Shows</a></li>
											<li><a href="#">Corporate</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="footer-widgets subscribe-widgets">
							<h4 class="footer-widgets-heading">{{$footer_translations->where('key', 'subscribe') ? $footer_translations->where('key', 'subscribe')->first->value->value : ''}}</h4>
							<p class="subscribe-text">{{$footer_translations->where('key', 'subscribe text') ? $footer_translations->where('key', 'subscribe text')->first->value->value : ''}}</p>
							{!! Form::open(['method' => 'POST', 'action' => 'emailSubscribe@subscribe']) !!}
							{{ csrf_field() }}
							<div class="form-group">
								<input type="email" name="email" class="form-control subscribe-input" placeholder="Enter your e-mail">
								<button type="submit" class="subscribe-btn"><i class="fa fa-long-arrow-alt-right"></i></button>
							</div>
							{!! Form::close() !!}
						</div>
					</div>
					<div class="col-md-2">
						<div class="footer-widgets social-widgets social-btns">
							<ul>
								<li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
								<li><a href="#" target="_blank"><i class="fa fa-youtube"></i></a></li>

							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="copyright-footer">
				@if(isset($copyright))
				{!! $copyright !!}
				@endif
			</div>
		</div>
	</footer>
	@endif
	<!-- end footer -->
	<!-- jquery -->
	<!-- <script src="js/main.js"></script>  -->
	<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>

	@if ( Route::currentRouteName() == 'showSeason' )
	<script type="text/javascript" src="{{asset('js/bootstrap-select.min.js')}}"></script>
	@endif

	<!-- <script type="text/javascript" src="{{asset('js/playlist.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/youtube-videojs.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/videojs-hls.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/vimeo.min.js')}}"></script> -->

	<script type="text/javascript" src="{{asset('js/jquery.popover.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/menumaker.js')}}"></script>
	<!-- <script type="text/javascript" src="{{asset('js/jquery.curtail.min.js')}}"></script> -->
	<script type="text/javascript" src="{{asset('js/owl.carousel.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/jquery.scrollSpeed.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/TweenMax.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/ScrollMagic.min.js')}}"></script>

	<!-- <script type="text/javascript" src="{{asset('js/videojs-playlist-ui.min.js')}}"></script> -->
	<script type="text/javascript" src="{{asset('js/animation.gsap.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/debug.addIndicators.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/modernizr-custom.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/theme.js')}}?v=1.1"></script>

	@yield('custom-script')
	<script>
		(function($) {
    // Session Popup
    $('.sessionmodal').addClass("active");
    setTimeout(function() {
    	$('.sessionmodal').removeClass("active");
    }, 7000);
})(jQuery);
</script>

@if($google)
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', '{{$google}}', 'auto');
	ga('send', 'pageview');

</script>
@endif
@if($fb)
<!-- facebook -->
<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','https://connect.facebook.net/en_US/fbevents.js');
// Insert Your Facebook Pixel ID below. 
fbq('init', '{{$fb}}');
fbq('track', 'PageView');
</script>
<!--End  facebook -->
@endif

@if($rightclick == 1)
<script type="text/javascript" language="javascript">
   // Right click disable 
   $(function() {
   	$(this).bind("contextmenu", function(inspect) {
   		inspect.preventDefault();
   	});
   });
      // End Right click disable 
  </script>
  @endif

  @if($inspect == 1)
  <script type="text/javascript" language="javascript">
//all controller is disable 
$(function() {
	var isCtrl = false;
	document.onkeyup=function(e){
		if(e.which == 17) isCtrl=false;
	}

	document.onkeydown=function(e){
		if(e.which == 17) isCtrl=true;
		if(e.which == 85 && isCtrl == true) {
			return false;
		}
	};
	$(document).keydown(function (event) {
       if (event.keyCode == 123) { // Prevent F12
       	return false;
       } 
      else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I
      	return false;
      }
  });  
});
     // end all controller is disable 
 </script>
 @endif


 @if($goto==1)
 <script type="text/javascript">
 //Go to top
 $(window).scroll(function() {
 	var height = $(window).scrollTop();
 	if (height > 100) {
 		$('#back2Top').fadeIn();
 	} else {
 		$('#back2Top').fadeOut();
 	}
 });
 $(document).ready(function() {
 	$("#back2Top").click(function(event) {
 		event.preventDefault();
 		$("html, body").animate({ scrollTop: 0 }, "slow");
 		return false;
 	});

 });
// end go to top 
</script>
@endif

@yield('javascripts')

</body>
<!--body end -->
</html>