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
	<title><?php echo e($w_title); ?></title>
	<meta charset="utf-8" />
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta name="Description" content="<?php echo e($description); ?>" />
	<meta name="keyword" content="<?php echo e($w_title); ?>, <?php echo e($keyword); ?>">
	<meta name="MobileOptimized" content="320" />
	<!-- CSRF Token -->
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
	<link rel="icon" type="image/icon" href="<?php echo e(asset('images/favicon/favicon.png')); ?>"> <!-- favicon-icon -->
	<!-- theme style -->
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> <!-- google font -->
	<link href="<?php echo e(asset('css/videojs-icons.css')); ?>" rel="stylesheet" type="text/css"/> <!-- google font -->
	<link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/> <!-- bootstrap css -->

	<?php if( Route::currentRouteName() == 'showSeason' ): ?>
	<link href="<?php echo e(asset('css/bootstrap-select.min.css')); ?>" rel="stylesheet" type="text/css"/>
	<?php endif; ?>

	<link href="https://vjs.zencdn.net/6.6.0/video-js.css" rel="stylesheet"> <!-- videojs css -->
	<link href="<?php echo e(asset('css/menumaker.css')); ?>" type="text/css" rel="stylesheet"> <!-- menu css -->
	<link href="<?php echo e(asset('css/owl.carousel.min.css')); ?>" rel="stylesheet" type="text/css"/> <!-- owl carousel css -->
	<link href="<?php echo e(asset('css/owl.theme.default.min.css')); ?>?v=1.2" rel="stylesheet" type="text/css"/> <!-- owl carousel css -->
	<link href="<?php echo e(asset('css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css"/> <!-- fontawsome css -->
	<link href="<?php echo e(asset('css/popover.css')); ?>" rel="stylesheet" type="text/css"/> <!-- bootstrap popover css -->
	<link href="<?php echo e(asset('css/layers.css')); ?>" rel="stylesheet" type="text/css"/> <!-- Revolution css -->
	<link href="<?php echo e(asset('css/navigation.css')); ?>" rel="stylesheet" type="text/css"/> <!-- Revolution css -->
	<link href="<?php echo e(asset('css/pe-icon-7-stroke.css')); ?>" rel="stylesheet" type="text/css"/> <!-- Revolution css -->
	<link href="<?php echo e(asset('css/settings.css')); ?>" rel="stylesheet" type="text/css"/> <!-- Revolution css -->
	
	<!-- <link href="<?php echo e(asset('css/videojs-playlist-ui.vertical.css')); ?>" rel="stylesheet" type="text/css"/> -->

	<?php if($color==1): ?> 
	<link href="<?php echo e(asset('css/style-light.css')); ?>" rel="stylesheet" type="text/css"/> <!-- custom css -->
	<?php else: ?>
	<link href="<?php echo e(asset('css/style.css')); ?>?v=1.2" rel="stylesheet" type="text/css"/> <!-- custom css -->
	<?php endif; ?>

	<link href="<?php echo e(asset('css/goto.css')); ?>" rel="stylesheet" type="text/css"/><!-- Go to Top css -->
	<script src="https://js.stripe.com/v3/"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
	<script>
		window.Laravel =  <?php echo json_encode([
			'csrfToken' => csrf_token(),
			]); ?>
		</script>
		<script type="text/javascript" src="<?php echo e(asset('js/app-dist.js')); ?>"></script> <!-- app library js -->
		<!-- end theme style -->

	</head>
	<!-- end head -->
	<!--body start-->
	<body>
		<!-- preloader -->
		<?php if($preloader == 1): ?>
		<div class="loading">
			<div class="logo">
				<img src="<?php echo e(asset('images/logo/'.$logo)); ?>" class="img-responsive" alt="<?php echo e($w_title); ?>">
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
		<?php endif; ?>
		<!-- end preloader -->
		<div class="body-overlay-bg"></div>
		<?php if(Session::has('added')): ?>
		<div id="sessionModal" class="sessionmodal rgba-green-strong z-depth-2">
			<i class="fa fa-check-circle"></i> <p><?php echo e(session('added')); ?></p>
		</div>
		<?php elseif(Session::has('updated')): ?>
		<div id="sessionModal" class="sessionmodal rgba-cyan-strong z-depth-2">
			<i class="fa fa-exclamation-triangle"></i> <p><?php echo e(session('updated')); ?></p>
		</div>
		<?php elseif(Session::has('deleted')): ?>
		<div id="sessionModal" class="sessionmodal rgba-red-strong z-depth-2">
			<i class="fa fa-window-close"></i> <p><?php echo e(session('deleted')); ?></p>
		</div>
		<?php endif; ?>
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
							<?php if(isset($logo) != null): ?>
							<a href="<?php echo e(isset($nav_menus[0]) ? route('home', strtolower($nav_menus[0]->name)) : '#'); ?>" title="<?php echo e($w_title); ?>"><img src="<?php echo e(asset('images/logo/'.$logo)); ?>" class="img-responsive" alt="<?php echo e($w_title); ?>"></a>
							<?php else: ?>
							<a href="<?php echo e(route('home', $nav_menus[0]->name)); ?>" title="<?php echo e($w_title); ?>"><img src="images/logo.png" class="img-responsive" alt="<?php echo e($w_title); ?>"></a>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-sm-4">
						<?php if(auth()->guard()->check()): ?>
						<?php
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
?>
<?php if($subscribed == 1): ?>
<div id="cssmenu">
	<?php if(isset($nav_menus) && count($nav_menus) > 0): ?>
	<ul>
		<?php $__currentLoopData = $nav_menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<li><a class="<?php echo e(Nav::hasSegment($menu->slug)); ?>" href="<?php echo e(url('/', $menu->slug)); ?>"  title="<?php echo e($menu->name); ?>"><?php echo e($menu->name); ?></a></li>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
	<?php endif; ?>
</div>
<?php endif; ?>
<?php endif; ?>
</div>
<div class="col-sm-6 pull-right">
	<div class="login-panel-main-block">
		<ul>
			<?php if(auth()->guard()->check()): ?>
			<?php if($subscribed == 1): ?>
			<li class="prime-search-block">
				<?php echo Form::open(['method' => 'GET', 'action' => 'HomeController@search', 'class' => 'search_form']); ?>

				<div class="aa-input-container" id="aa-input-container">
					<?php echo Form::text('search', null, ['class' => 'search-input', 'placeholder' => 'Search','required']); ?>

					<button type="submit" class="search-button"><i class="fa fa-search"></i>
					</button>
				</div>
				<?php echo Form::close(); ?>

			</li>
			<?php endif; ?>
			<?php if(isset($languages) && count($languages) > 0): ?>
			<li class="sign-in-block language-switch-block">
				<div class="dropdown prime-dropdown">
					<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-globe"></i> <?php echo e(Session::has('changed_language') ? ucfirst(Session::get('changed_language')) : ''); ?></button>
					<span class="caret"></span></button>
					<ul class="dropdown-menu prime-dropdown-menu">
						<?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><a href="<?php echo e(route('languageSwitch', $language->local)); ?>"><?php echo e($language->name); ?></a></li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</ul>
				</div>
			</li>
			<?php endif; ?>
			<li class="sign-in-block">
				<div class="dropdown prime-dropdown">
					<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-user-circle"></i> <?php echo e($auth ? $auth->name : ''); ?>

						<span class="caret"></span></button>
						<ul class="dropdown-menu prime-dropdown-menu">
							<?php if($auth->is_admin == 1): ?>
							<li><a href="<?php echo e(url('admin')); ?>" target="_blank">Admin <?php echo e($header_translations->where('key', 'dashboard') ? $header_translations->where('key', 'dashboard')->first->value->value : ''); ?></a></li>
							<?php endif; ?>
							<?php if($subscribed == 1): ?>
							<li><a href="<?php echo e(url('account/watchlist/movies')); ?>" class="active"><?php echo e($header_translations->where('key', 'watchlist')->first->value->value); ?></a></li>
							<?php else: ?>  
							<li><a href="<?php echo e(url('account/purchaseplan')); ?>">Subscribe</a></li>
							<?php endif; ?>
							<li><a href="<?php echo e(url('account')); ?>"><?php echo e($header_translations->where('key', 'dashboard') ? $header_translations->where('key', 'dashboard')->first->value->value : ''); ?></a></li>
							<li><a href="<?php echo e(url('faq')); ?>"><?php echo e($header_translations->where('key', 'faqs') ? $header_translations->where('key', 'faqs')->first->value->value : ''); ?></a></li>
							<li>
								<a href="<?php echo e(route('logout')); ?>"
								onclick="event.preventDefault();
								document.getElementById('logout-form').submit();">
								<?php echo e($header_translations->where('key', 'sign out') ? $header_translations->where('key', 'sign out')->first->value->value : ''); ?>

							</a>
							<form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
								<?php echo e(csrf_field()); ?>

							</form>
						</li>
					</ul>
				</div>
			</li>
			<?php else: ?>
			<li class="sign-in-block mrgn-rt-20"><a class="sign-in" href="<?php echo e(url('login')); ?>"><i class="fa fa-sign-in"></i> <?php echo e($header_translations->where('key', 'sign in') ? $header_translations->where('key', 'sign in')->first->value->value : ''); ?></a></li>
			<li class="sign-in-block"><a class="sign-in" href="<?php echo e(url('register')); ?>"><i class="fa fa-user-plus"></i> <?php echo e($header_translations->where('key', 'register') ? $header_translations->where('key', 'register')->first->value->value : ''); ?></a></li>  
			<?php endif; ?> 
		</ul>
	</div>
</div>
</div>
</div>
</div>
<div>
	
	<!-- end navigation -->
	<?php echo $__env->yieldContent('main-wrapper'); ?>
	<!-- footer -->
	<?php if($prime_footer == 1): ?>
	<footer id="prime-footer" class="prime-footer-main-block">
		<div class="container-fluid">
			<div style="height:0px;">
				<a id="back2Top" title="Back to top" href="#">&#10148;</a>
			</div>
			<div class="logo">
				<img src="<?php echo e(asset('images/logo/'.$logo)); ?>" class="img-responsive" alt="<?php echo e($w_title); ?>">
			</div>
			<div class="copyright">
				<ul>
					<li>
						<?php if(isset($copyright)): ?>
						<?php echo $copyright; ?>

						<?php endif; ?>
					</li>
				</ul>
				<ul>
					<li><a href="<?php echo e(url('terms_condition')); ?>"><?php echo e($footer_translations->where('key', 'terms and condition') ? $footer_translations->where('key', 'terms and condition')->first->value->value : ''); ?></a></li>
					<li><a href="<?php echo e(url('privacy_policy')); ?>"><?php echo e($footer_translations->where('key', 'privacy policy') ? $footer_translations->where('key', 'privacy policy')->first->value->value : ''); ?></a></li>
					<li><a href="<?php echo e(url('refund_policy')); ?>"><?php echo e($footer_translations->where('key', 'refund policy') ? $footer_translations->where('key', 'refund policy')->first->value->value : ''); ?></a></li>
					<li><a href="<?php echo e(url('faq')); ?>"><?php echo e($footer_translations->where('key', 'help') ? $footer_translations->where('key', 'help')->first->value->value : ''); ?></a></li>
				</ul>
			</div>
		</div>
	</footer>
	<?php else: ?>
	<footer id="footer-main-block" class="footer-main-block">
		<div class="pre-footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3">
						<div class="footer-logo footer-widgets">
							<?php if(isset($logo)): ?>
							<img src="<?php echo e(asset('images/logo/'.$logo)); ?>" class="img-responsive" alt="<?php echo e($w_title); ?>">
							<?php endif; ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="footer-widgets">
							<div class="row">
								<div class="col-md-6">
									<div class="footer-links-block">
										<h4 class="footer-widgets-heading"><?php echo e($footer_translations->where('key', 'corporate') ? $footer_translations->where('key', 'corporate')->first->value->value : ''); ?></h4>
										<ul>
											<li><a href="<?php echo e(url('terms_condition')); ?>"><?php echo e($footer_translations->where('key', 'terms and condition') ? $footer_translations->where('key', 'terms and condition')->first->value->value : ''); ?></a></li>
											<li><a href="<?php echo e(url('privacy_policy')); ?>"><?php echo e($footer_translations->where('key', 'privacy policy') ? $footer_translations->where('key', 'privacy policy')->first->value->value : ''); ?></a></li>
											<li><a href="<?php echo e(url('refund_policy')); ?>"><?php echo e($footer_translations->where('key', 'refund policy') ? $footer_translations->where('key', 'refund policy')->first->value->value : ''); ?></a></li>
											<li><a href="<?php echo e(url('faq')); ?>"><?php echo e($footer_translations->where('key', 'help') ? $footer_translations->where('key', 'help')->first->value->value : ''); ?></a></li>

										</ul>
									</div>
								</div>
								<div class="col-md-6">
									<div class="footer-links-block">
										<h4 class="footer-widgets-heading"><?php echo e($footer_translations->where('key', 'sitemap') ? $footer_translations->where('key', 'sitemap')->first->value->value : ''); ?></h4>
										<ul>
											<li><a href="<?php echo e(url('home')); ?>">Home</a></li>
											<li><a href="<?php echo e(url('movies')); ?>">Movies</a></li>
											<li><a href="<?php echo e(url('tvseries')); ?>">Tv Shows</a></li>
											<li><a href="#">Corporate</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="footer-widgets subscribe-widgets">
							<h4 class="footer-widgets-heading"><?php echo e($footer_translations->where('key', 'subscribe') ? $footer_translations->where('key', 'subscribe')->first->value->value : ''); ?></h4>
							<p class="subscribe-text"><?php echo e($footer_translations->where('key', 'subscribe text') ? $footer_translations->where('key', 'subscribe text')->first->value->value : ''); ?></p>
							<?php echo Form::open(['method' => 'POST', 'action' => 'emailSubscribe@subscribe']); ?>

							<?php echo e(csrf_field()); ?>

							<div class="form-group">
								<input type="email" name="email" class="form-control subscribe-input" placeholder="Enter your e-mail">
								<button type="submit" class="subscribe-btn"><i class="fa fa-long-arrow-alt-right"></i></button>
							</div>
							<?php echo Form::close(); ?>

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
				<?php if(isset($copyright)): ?>
				<?php echo $copyright; ?>

				<?php endif; ?>
			</div>
		</div>
	</footer>
	<?php endif; ?>
	<!-- end footer -->
	<!-- jquery -->
	<!-- <script src="js/main.js"></script>  -->
	<script type="text/javascript" src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>

	<?php if( Route::currentRouteName() == 'showSeason' ): ?>
	<script type="text/javascript" src="<?php echo e(asset('js/bootstrap-select.min.js')); ?>"></script>
	<?php endif; ?>

	<!-- <script type="text/javascript" src="<?php echo e(asset('js/playlist.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/youtube-videojs.min.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/videojs-hls.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/vimeo.min.js')); ?>"></script> -->

	<script type="text/javascript" src="<?php echo e(asset('js/jquery.popover.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/menumaker.js')); ?>"></script>
	<!-- <script type="text/javascript" src="<?php echo e(asset('js/jquery.curtail.min.js')); ?>"></script> -->
	<script type="text/javascript" src="<?php echo e(asset('js/owl.carousel.min.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/jquery.scrollSpeed.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/TweenMax.min.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/ScrollMagic.min.js')); ?>"></script>

	<!-- <script type="text/javascript" src="<?php echo e(asset('js/videojs-playlist-ui.min.js')); ?>"></script> -->
	<script type="text/javascript" src="<?php echo e(asset('js/animation.gsap.min.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/debug.addIndicators.min.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/modernizr-custom.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('js/theme.js')); ?>?v=1.1"></script>

	<?php echo $__env->yieldContent('custom-script'); ?>
	<script>
		(function($) {
    // Session Popup
    $('.sessionmodal').addClass("active");
    setTimeout(function() {
    	$('.sessionmodal').removeClass("active");
    }, 7000);
})(jQuery);
</script>

<?php if($google): ?>
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', '<?php echo e($google); ?>', 'auto');
	ga('send', 'pageview');

</script>
<?php endif; ?>
<?php if($fb): ?>
<!-- facebook -->
<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','https://connect.facebook.net/en_US/fbevents.js');
// Insert Your Facebook Pixel ID below. 
fbq('init', '<?php echo e($fb); ?>');
fbq('track', 'PageView');
</script>
<!--End  facebook -->
<?php endif; ?>

<?php if($rightclick == 1): ?>
<script type="text/javascript" language="javascript">
   // Right click disable 
   $(function() {
   	$(this).bind("contextmenu", function(inspect) {
   		inspect.preventDefault();
   	});
   });
      // End Right click disable 
  </script>
  <?php endif; ?>

  <?php if($inspect == 1): ?>
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
 <?php endif; ?>


 <?php if($goto==1): ?>
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
<?php endif; ?>

</body>
<!--body end -->
</html>