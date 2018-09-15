<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin - {{$w_title}}</title>
  <!-- favicon-icon -->
  <link rel="icon" type="image/icon" href="{{asset('images/favicon/favicon.png')}}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
  <!-- Jquery Ui Css -->
  <link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/jquery-jvectormap.css')}}">
  <!-- Admin (main) Style Sheet -->
  <link rel="stylesheet" href="{{asset('css/admin.css')}}">
  <script>
    window.Laravel =  <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
  </script>
</head>
  <body class="hold-transition skin-blue">
    <div class="loading-block">
      <div class="loading z-depth-4"></div>
    </div>
<div class="wrapper">
  <!-- Main Header -->
  <header class="main-header">
    <!-- Logo -->
    <a href="{{url('/admin')}}" class="logo" title="{{$w_title}}">
      @if(isset($logo))
        <img src="{{asset('images/logo/'.$logo)}}" class="img-responsive" alt="{{$w_title}}">
      @endif
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      @if (isset($nav_menus) && count($nav_menus) > 0)
        <a href="{{isset($nav_menus[0]) ? route('home', $nav_menus[0]->slug) : '#'}}" target="_blank" class="visit-site-btn btn" title="Visit Site">Visit Site <i class="material-icons right">keyboard_arrow_right</i></a>
      @else   
        <a href="#" data-toggle="tooltip" data-placement="bottom" data-original-title="Please create at least one menu to visit the site" class="visit-site-btn btn">Visit Site <i class="material-icons right">keyboard_arrow_right</i></a>
      @endif
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown admin-nav">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="material-icons">language</i> {{Session::has('changed_language') ? Session::get('changed_language') : ''}}</button>
            <ul class="dropdown-menu animated flipInX">
              @if (isset($languages) && count($languages) > 0)
                @foreach ($languages as $language)
                  <li><a href="{{ route('languageSwitch', $language->local) }}">{{$language->name}} ({{$language->local}})</a></li>
                @endforeach
              @endif
            </ul>
          </li>
          <li class="dropdown admin-nav">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="material-icons">account_circle</i></button>
            <ul class="dropdown-menu animated flipInX">
              <li><a href="{{url('admin/profile')}}" title="My Profile">My Profile</a></li>
              <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();" title="logout">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar" style="background-image: url({{asset('images/sidebar-7.jpg')}});">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <i class="material-icons">account_circle</i>
        </div>
        <div class="pull-left info">
          <h4 class="user-name">{{ucfirst($auth->name)}}</h4>
          <p>Admin</p>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <!-- Optionally, you can add icons to the links -->
        <li><a class="{{ Nav::isRoute('dashboard') }}" href="{{url('/admin')}}" title="Dashboard"><i class="material-icons">dashboard</i> <span>Dashboard</span></a></li>
        <li><a class="{{ Nav::isResource('users') }}" href="{{url('/admin/users')}}" title="Users"><i class="material-icons">people</i> <span>Users</span></a></li>
        <li><a class="{{ Nav::isResource('plan') }}" href="{{url('/admin/plan')}}" title="Active Plan"><i class="material-icons">description</i> <span>Users Subscription</span></a></li>
        <li><a class="{{ Nav::isResource('movies') }}" href="{{url('/admin/movies')}}" title="Movies"><i class="material-icons">ondemand_video</i> <span>Movies</span></a></li>
        <li><a class="{{ Nav::isResource('tvseries') }}" href="{{url('/admin/tvseries')}}" title="TV Series"><i class="material-icons">movie_filter</i> <span>TV Series</span></a></li>
        <li><a class="{{ Nav::isResource('directors') }}" href="{{url('/admin/directors')}}" title="Directors"><i class="material-icons">stars</i> <span>Directors</span></a></li>
        <li><a class="{{ Nav::isResource('actors') }}" href="{{url('/admin/actors')}}" title="Actors"><i class="material-icons">star_border</i> <span>Actors</span></a></li>
        <li><a class="{{ Nav::isResource('genres') }}" href="{{url('/admin/genres')}}" title="Genres"><i class="material-icons">filter_list</i> <span>Genres</span></a></li>
        <li><a class="{{ Nav::isResource('audio_language') }}" href="{{url('/admin/audio_language')}}" title="Audio Languages"><i class="material-icons">queue_music</i> <span>Audio Languages</span></a></li>
        <li><a class="{{ Nav::isResource('languages') }}" href="{{url('/admin/languages')}}" title="Languages"><i class="material-icons">language</i> <span>Languages</span></a></li>
        <li><a class="{{ Nav::isResource('menu') }}" href="{{url('/admin/menu')}}" title="Menu"><i class="material-icons">menu</i> <span>Menu / Navigation</span></a></li>
        <li><a class="{{ Nav::isResource('packages') }}" href="{{url('/admin/packages')}}" title="Packages"><i class="material-icons">poll</i> <span>Packages</span></a></li>
        <li><a class="{{ Nav::isResource('coupons') }}" href="{{url('/admin/coupons')}}" title="Stripe Coupons"><i class="material-icons">more</i> <span>Stripe Coupons</span></a></li>
        <li><a class="{{ Nav::isResource('home_slider') }}" href="{{url('/admin/home_slider')}}" title="Slider Settings"><i class="material-icons">view_carousel</i> <span>Slider Settings</span></a></li>
        <li class="treeview">
          <a href="#" class="{{ Nav::isResource('customize') }}" title="Site Customization">
            <i class="material-icons">view_quilt</i> <span>Site Customization</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Nav::isResource('landing-page') }}"><a href="{{url('admin/customize/landing-page')}}" title="Landing Page"><i class="fa fa-circle-o"></i> Landing Page</a></li>
            <li class="{{ Nav::isResource('auth-page-customize') }}"><a href="{{url('admin/customize/auth-page-customize')}}" title="Login"><i class="fa fa-circle-o"></i> Sign In / Sign Up</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#" class="{{ Nav::isResource('settings') }} {{ Nav::isRoute('term_con') }} {{ Nav::isRoute('pri_pol') }} {{ Nav::isRoute('copyright') }}" title="Site Settings">
            <i class="material-icons">settings</i> <span>Site Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Nav::isResource('settings') }}"><a href="{{url('admin/settings')}}" title="General Settings"><i class="fa fa-circle-o"></i> General Settings</a></li>
            <li class="{{ Nav::isResource('seo') }}"><a href="{{url('admin/seo')}}" title="SEO"><i class="fa fa-circle-o"></i> SEO</a></li>
            <li class="{{ Nav::isResource('api-settings') }}"><a href="{{url('admin/api-settings')}}" title="API Settings"><i class="fa fa-circle-o"></i> API Settings</a></li>
            <li class="{{ Nav::isRoute('term_con') }}"><a href="{{url('admin/term&con')}}" title="Terms &amp; Condition"><i class="fa fa-circle-o"></i> Terms &amp; Condition</a></li>
            <li class="{{ Nav::isRoute('pri_pol') }}"><a href="{{url('admin/pri_pol')}}" title="Privacy Policy"><i class="fa fa-circle-o"></i> Privacy Policy</a></li>
            <li class="{{ Nav::isRoute('refund_pol') }}"><a href="{{url('admin/refund_pol')}}" title="Refund Policy"><i class="fa fa-circle-o"></i> Refund Policy</a></li>
            <li class="{{ Nav::isRoute('copyright') }}"><a href="{{url('admin/copyright')}}" title="Copyright"><i class="fa fa-circle-o"></i> Copyright</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#" class="{{ Nav::isResource('translation') }}">
            <i class="material-icons">translate</i> <span>Translations</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ Nav::isRoute('header-translation-index') }}"><a href="{{url('admin/header-translations')}}" title="Header"><i class="fa fa-circle-o"></i>Header</a></li>
            <li class="{{ Nav::isRoute('footer-translation-index') }}"><a href="{{url('admin/footer-translations')}}" title="Footer"><i class="fa fa-circle-o"></i>Footer</a></li>
            <li class="{{ Nav::isRoute('home-translation-index') }}"><a href="{{url('admin/home-translations')}}" title="Home Page"><i class="fa fa-circle-o"></i>Home Page</a></li>
            <li class="{{ Nav::isRoute('popover-detail-translations-index') }}"><a href="{{url('admin/popover-detail-translations')}}" title="Popover Detail"><i class="fa fa-circle-o"></i>Popover Detail</a></li>
          </ul>
        </li>
        <li><a class="{{ Nav::isResource('faqs') }}" href="{{url('/admin/faqs')}}" title="FAQ's"><i class="material-icons">question_answer</i> <span>FAQ's</span></a></li>
        <li><a class="{{ Nav::isResource('report') }}" href="{{url('/admin/report')}}" title="Reports"><i class="material-icons">assignment</i> <span>Reports</span></a></li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @if (Session::has('added'))
      <div id="sessionModal" class="sessionmodal rgba-green-strong z-depth-2">
        <i class="fa fa-check-circle"></i> <p>{{session('added')}}</p>
      </div>
    @elseif (Session::has('updated'))
      <div id="sessionModal" class="sessionmodal rgba-cyan-strong z-depth-2">
        <i class="fa fa-check-circle"></i> <p>{{session('updated')}}</p>
      </div>
    @elseif (Session::has('deleted'))
      <div id="sessionModal" class="sessionmodal rgba-red-strong z-depth-2">
        <i class="fa fa-window-close"></i> <p>{{session('deleted')}}</p>
      </div>
    @endif  
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
        @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Main Footer -->
</div>
<!-- ./wrapper -->
<!-- Admin Js -->
<script src="{{asset('js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/admin-dist.js')}}" type="text/javascript"></script>
<script src="{{asset('js/app-dist.js')}}" type="text/javascript"></script>
<script src="{{asset('js/ckeditor.js')}}" type="text/javascript"></script>
<script src="{{asset('js/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/chart.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/utils.js')}}" type="text/javascript"></script>
<script src="{{asset('js/jquery-jvectormap-1.2.2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/jquery-jvectormap-world-mill-en.js')}}" type="text/javascript"></script>
<script>
  $(function () {
    // DataTables
    $('#movies_table').DataTable({
      responsive: true,
      "sDom": "<'row'><'row'<'col-md-4'l><'col-md-4'B><'col-md-4'f>r>t<'row'<'col-sm-12'p>>",
      "language": {
        "paginate": {
          "previous": '<i class="material-icons paginate-btns">keyboard_arrow_left</i>',
          "next": '<i class="material-icons paginate-btns">keyboard_arrow_right</i>'
          }
      },
      buttons: [
        {
          extend: 'print',
          exportOptions: {
              columns: ':visible'
          }
        },
        'csvHtml5',
        'excelHtml5',
        'colvis',
      ]
    });

    $('#full_detail_table').DataTable({
      responsive: true,
      "sDom": "<'row'><'row'<'col-md-4'l><'col-md-4'B><'col-md-4'f>r>t<'row'<'col-sm-12'p>>",
      "language": {
      "paginate": {
        "previous": '<i class="material-icons paginate-btns">keyboard_arrow_left</i>',
        "next": '<i class="material-icons paginate-btns">keyboard_arrow_right</i>'
        }
      },
      buttons: [
        {
          extend: 'print',
          exportOptions: {
              columns: ':visible'
          }
        },
        'csvHtml5',
        'excelHtml5',
        'colvis',
      ]
    });
    $(".js-select2").select2({
        placeholder: "Pick states",
        theme: "material"
    });
    
    $(".select2-selection__arrow")
        .addClass("material-icons")
        .html("arrow_drop_down");
  });
</script>
@yield('custom-script')
</body>
</html>
