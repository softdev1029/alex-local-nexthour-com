@extends('layouts.admin')
@section('content')
  <div class="content-main-block mrg-t-40">
  	<h4 class="admin-form-text">Dashboard</h4>
    <div class="row">
    	<div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="{{url('admin/users')}}" class="small-box z-depth-1 hoverable bg-aqua default-color">
          <div class="inner">
            <h3>{{$users_count}}</h3>
            <p>Total Users</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="{{url('admin/movies')}}" class="small-box z-depth-1 hoverable bg-red danger-color">
          <div class="inner">
            <h3>{{$movies_count}}</h3>
            <p>Total Movies</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="{{url('admin/tvseries')}}" class="small-box z-depth-1 hoverable bg-green success-color">
          <div class="inner">
            <h3>{{$tvseries_count}}</h3>
            <p>Total Tv Serieses</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="{{url('admin/packages')}}" class="small-box z-depth-1 hoverable bg-yellow secondary-color">
          <div class="inner">
            <h3>{{$package_count}}</h3>
            <p>Total Packages</p>
          </div>
          <div class="icon">
            <i class="fa fa-files-o"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="{{url('admin/coupons')}}" class="small-box z-depth-1 hoverable bg-green warning-color">
          <div class="inner">
            <h3>{{$coupon_count}}</h3>
            <p>Total Coupons</p>
          </div>
          <div class="icon">
            <i class="fa fa-bookmark-o"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="{{url('admin/faqs')}}" class="small-box z-depth-1 hoverable bg-yellow pink darken-4">
          <div class="inner">
            <h3>{{$faq_count}}</h3>
            <p>Total Faqs</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="{{url('admin/genres')}}" class="small-box z-depth-1 hoverable bg-aqua  grey darken-2">
          <div class="inner">
            <h3>{{$genres_count}}</h3>
            <p>Total Genres</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
        </a>
      </div>
    </div>
  </div>
@endsection
{{-- @section('custom-script')
  <script>
    $(function () {



        /* jVector Maps
       * 
       * Create a world map with markers
       */
      $('#world-map-markers').vectorMap({
        map              : 'world_mill_en',
        normalizeFunction: 'polynomial',
        hoverOpacity     : 0.7,
        hoverColor       : false,
        backgroundColor  : 'transparent',
        regionStyle      : {
          initial      : {
            fill            : 'rgba(210, 214, 222, 1)',
            'fill-opacity'  : 1,
            stroke          : 'none',
            'stroke-width'  : 0,
            'stroke-opacity': 1
          },
          hover        : {
            'fill-opacity': 0.7,
            cursor        : 'pointer'
          },
          selected     : {
            fill: 'yellow'
          },
          selectedHover: {}
        },
        markerStyle      : {
          initial: {
            fill  : '#00a65a',
            stroke: '#111'
          }
        },
        markers: [
          { latLng: [41.90, 12.45], name: 'Vatican City' },
          { latLng: [43.73, 7.41], name: 'Monaco' },
          { latLng: [-0.52, 166.93], name: 'Nauru' },
          { latLng: [-8.51, 179.21], name: 'Tuvalu' },
          { latLng: [43.93, 12.46], name: 'San Marino' },
          { latLng: [47.14, 9.52], name: 'Liechtenstein' },
          { latLng: [7.11, 171.06], name: 'Marshall Islands' },
          { latLng: [17.3, -62.73], name: 'Saint Kitts and Nevis' },
          { latLng: [3.2, 73.22], name: 'Maldives' },
          { latLng: [35.88, 14.5], name: 'Malta' },
          { latLng: [12.05, -61.75], name: 'Grenada' },
          { latLng: [13.16, -61.23], name: 'Saint Vincent and the Grenadines' },
          { latLng: [13.16, -59.55], name: 'Barbados' },
          { latLng: [17.11, -61.85], name: 'Antigua and Barbuda' },
          { latLng: [-4.61, 55.45], name: 'Seychelles' },
          { latLng: [7.35, 134.46], name: 'Palau' },
          { latLng: [42.5, 1.51], name: 'Andorra' },
          { latLng: [14.01, -60.98], name: 'Saint Lucia' },
          { latLng: [6.91, 158.18], name: 'Federated States of Micronesia' },
          { latLng: [1.3, 103.8], name: 'Singapore' },
          { latLng: [1.46, 173.03], name: 'Kiribati' },
          { latLng: [-21.13, -175.2], name: 'Tonga' },
          { latLng: [15.3, -61.38], name: 'Dominica' },
          { latLng: [-20.2, 57.5], name: 'Mauritius' },
          { latLng: [26.02, 50.55], name: 'Bahrain' },
          { latLng: [0.33, 6.73], name: 'São Tomé and Príncipe' }
        ]
      });
    });  
  </script>  
  <script>
      var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var config = {
      type: 'line',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
          label: 'Monthly Sales',
          fill: true,
          backgroundColor: window.chartColors.red,
          borderColor: window.chartColors.black,
          pointStyle: 'line',
          data: [
            100, 65, 84, 100, 90, 120, 150
          ],
        }]
      },
      options: {
        responsive: true,
        legend: false,
        title: {
          display: false,
          text: 'Chart.js Line Chart'
        },
        tooltips: {
          mode: 'index',
          intersect: false,
        },
        hover: {
          mode: 'nearest',
          intersect: true
        },
        scales: {
          xAxes: [{
            display: false,
            scaleLabel: {
              display: true,
              labelString: 'Month'
            }
          }],
          yAxes: [{
            display: false,
            scaleLabel: {
              display: true,
              labelString: 'Value'
            }
          }]
        }
      }
    };

    window.onload = function() {
      var ctx = document.getElementById('canvas').getContext('2d');
      window.myLine = new Chart(ctx, config);
    };

  </script>  
@endsection --}}