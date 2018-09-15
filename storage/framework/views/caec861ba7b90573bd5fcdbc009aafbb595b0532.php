<!DOCTYPE html>
<html>
<head>
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
	<link rel="icon" type="image/icon" href="images/favicon/favicon.ico"> <!-- favicon-icon -->
	<!-- theme style -->
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> <!-- google font -->
	<link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/> <!-- bootstrap css -->
	<link href="https://vjs.zencdn.net/6.6.0/video-js.css" rel="stylesheet"> <!-- videojs css -->
	<link href="<?php echo e(asset('css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css"/> <!-- fontawsome css -->
	<link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet" type="text/css"/> <!-- custom css -->

	<script type="text/javascript" src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
</head>
<body class="bg-black">
	<div class="signup__container container sign-in-main-block">
		<div class="row"> 
			<div class="col-md-offset-2 col-md-4 pad-0">
				<div class="container__child signup__thumbnail" style="background-image: url(<?php echo e(asset('images/login/'.$auth_customize->image)); ?>);">
					<div class="thumbnail__logo">
						<?php if($logo != null): ?>
						<a href="<?php echo e(url('/')); ?>" title="<?php echo e($w_title); ?>"><img src="<?php echo e(asset('images/logo/'.$logo)); ?>" class="img-responsive" alt="<?php echo e($w_title); ?>"></a>
						<?php endif; ?>
					</div>
					<div class="thumbnail__content text-center">
						<?php echo $auth_customize->detail; ?>

					</div>
          <!-- <div class="thumbnail__links">
            <ul class="list-inline m-b-0 text-center">
              <li><a href="#" target="_blank"><i class="fa fa-globe"></i></a></li>
              <li><a href="https://www.behance.net/" target="_blank"><fa class="fa fa-behance"></fa></a></li>
              <li><a href="https://github.com/" target="_blank"><i class="fa fa-github"></i></a></li>
              <li><a href="https://twitter.com/" target="_blank"><i class="fa fa-twitter"></i></a></li>
            </ul>
        </div> -->
        <div class="signup__overlay"></div>
    </div>
</div>
<div class="col-md-4 pad-0">
	<div class="container__child signup__form">
		<form method="POST" action="<?php echo e(route('login')); ?>">
			<?php echo e(csrf_field()); ?>

			<div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
				<label for="email">Email</label>
				<input id="email" type="text" class="form-control" name="email" placeholder="Please Enter Your Email" value="<?php echo e(old('email')); ?>" required autofocus>
				<?php if($errors->has('email')): ?>
				<span class="help-block">
					<strong><?php echo e($errors->first('email')); ?></strong>
				</span>
				<?php endif; ?>
			</div>
			<div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
				<label for="password">Password</label>
				<input id="password" type="password" class="form-control" name="password" placeholder="Please Enter Your Password" value="<?php echo e(old('password')); ?>" required>
				<?php if($errors->has('password')): ?>
				<span class="help-block">
					<strong><?php echo e($errors->first('password')); ?></strong>
				</span>
				<?php endif; ?>
			</div>
			<div class="m-t-lg">
				<input class="btn btn--form btn--form-login" type="submit" value="Login" />
				<a class="signup__link pull-left" href="<?php echo e(route('password.request')); ?>">Forgot Your Password?</a>
				<a class="signup__link pull-right" href="<?php echo e(url('register')); ?>">Not a Member ? Register Here</a>
			</div>
		</form>  
	</div>
</div>
</div>
</div>
<!-- Scripts -->
<script type="text/javascript" src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>

<!-- <script type="text/javascript" src="<?php echo e(asset('js/videojs-playlist-ui.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/youtube-videojs.min.js')); ?>"></script> -->

<script type="text/javascript" src="<?php echo e(asset('js/jquery.popover.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/menumaker.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/jquery.curtail.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/owl.carousel.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/jquery.scrollSpeed.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/TweenMax.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/ScrollMagic.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/animation.gsap.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/debug.addIndicators.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/modernizr-custom.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('js/theme.js')); ?>"></script>
</body>
</html>