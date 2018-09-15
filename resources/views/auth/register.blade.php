<!DOCTYPE html>
<html>
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" type="image/icon" href="images/favicon/favicon.ico"> <!-- favicon-icon -->
	<!-- theme style -->
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> <!-- google font -->
	<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/> <!-- bootstrap css -->
	<link href="https://vjs.zencdn.net/6.6.0/video-js.css" rel="stylesheet"> <!-- videojs css -->
	<link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/> <!-- fontawsome css -->
	<link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css"/> <!-- custom css -->

	<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
</head>
<body class="bg-black">
	<div class="signup__container container">
		<div class="row"> 
			<div class="col-md-offset-2 col-md-4 pad-0">
				<div class="container__child signup__thumbnail" style="background-image: url({{ asset('images/login/'.$auth_customize->image) }});">
					<div class="thumbnail__logo">
						@if($logo != null)
						<a href="{{url('/')}}" title="{{$w_title}}"><img src="{{asset('images/logo/'.$logo)}}" class="img-responsive" alt="{{$w_title}}"></a>
						@endif
					</div>
					<div class="thumbnail__content text-center">
						{!! $auth_customize->detail !!}
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
		<form method="POST" action="{{ route('register') }}">
			{{ csrf_field() }}
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				<label for="name">Username</label>
				<input id="name" type="text" class="form-control" name="name" placeholder="Please Enter Your Username" value="{{ old('name') }}" required autofocus>
				@if ($errors->has('name'))
				<span class="help-block">
					<strong>{{ $errors->first('name') }}</strong>
				</span>
				@endif
			</div>
			<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				<label for="email">Email</label>
				<input id="email" type="text" class="form-control" name="email" placeholder="Please Enter Your Email" value="{{ old('email') }}" required autofocus>
				@if ($errors->has('email'))
				<span class="help-block">
					<strong>{{ $errors->first('email') }}</strong>
				</span>
				@endif
			</div>
			<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				<label for="password">Password</label>
				<input id="password" type="password" class="form-control" name="password" placeholder="Please Enter Your Password" value="{{ old('password') }}" required>
				@if ($errors->has('password'))
				<span class="help-block">
					<strong>{{ $errors->first('password') }}</strong>
				</span>
				@endif
			</div>
			<div class="form-group">
				<label for="password-confirm">Repeat Password</label>
				<input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Please Enter Your Password Again" required>
			</div>
			<div class="m-t-lg">
				<ul class="list-inline">
					<li>
						<input class="btn btn--form" type="submit" value="Register" />
					</li>
					<li>
						<a class="signup__link" href="{{url('login')}}">I am already a member</a>
					</li>
				</ul>
			</div>
		</form>  
	</div>
</div>
</div>
</div>
<!-- Scripts -->
<script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>

<!-- <script type="text/javascript" src="{{asset('js/videojs-playlist-ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/youtube-videojs.min.js')}}"></script> -->

<script type="text/javascript" src="{{asset('js/jquery.popover.js')}}"></script>
<script type="text/javascript" src="{{asset('js/menumaker.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.curtail.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/owl.carousel.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.scrollSpeed.js')}}"></script>
<script type="text/javascript" src="{{asset('js/TweenMax.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/ScrollMagic.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/animation.gsap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/debug.addIndicators.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/modernizr-custom.js')}}"></script>
<script type="text/javascript" src="{{asset('js/theme.js')}}"></script>
</body>
</html>