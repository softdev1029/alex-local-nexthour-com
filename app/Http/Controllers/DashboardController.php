<?php

namespace App\Http\Controllers;

use App\CouponCode;
use App\Faq;
use App\Genre;
use App\Movie;
use App\Package;
use App\TvSeries;
use App\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
    	$users_count = User::count();
    	$movies_count = Movie::count();
    	$tvseries_count = TvSeries::count();
    	$genres_count = Genre::count();	
    	$package_count = Package::count();
    	$coupon_count = CouponCode::count();
    	$faq_count = Faq::count();

        // $sales_chart = new SalesChart;
        // $sales_chart->labels(['One', 'Two', 'Three', 'Four', 'Five'])->dataset('Sample', 'line', [[100, 65, 84, 45, 90],'point' => ['pointStyle' => 'line']])->options(['borderColor' => '#111', 'borderWidth' => '2px', 'backgroundColor' => 'rgba(255,82,82,0.7)']);

        // $visitors_chart = new VisitorsChart;
        // $visitors_chart->labels(['One', 'Two', 'Three', 'Four', 'Five', 'six', 'ss'])->dataset('Sample', 'line', [0, 65, 84, 45, 90, 10])->options(['borderColor' => '#111', 'borderWidth' => '2px', 'backgroundColor' => 'rgba(255,82,82,0.7)']);

    	return view('admin.index', compact('genres_count','users_count', 'movies_count', 'tvseries_count', 'package_count', 'coupon_count', 'faq_count', 'sales_chart', 'visitors_chart'));
    }
}
