<?php

use App\Faq;
use App\Package;

// Routes With Only Language Switch Middleware 
Route::group(['middleware' => ['switch_languages']], function (){
	Route::get('/', 'HomeController@mainPage');
	Route::get('faq', function (){
		$faqs = Faq::all();
		return view('faq', compact('faqs'));
	});
	Route::view('terms_condition', 'term_condition');
	Route::view('privacy_policy', 'privacy_pol');
	Route::view('refund_policy', 'refund_pol');
	Route::view('seo', 'seo');
  // Auth Routes
	Auth::routes();
});

Route::get('language-switch/{local}', 'LanguageSwitchController@languageSwitch')->name('languageSwitch');

// Routes With Web, Auth Middlewares
Route::group(['middleware' => ['web', 'auth']], function () {
    // User Account routes without subscription
	Route::get('account', 'UserAccountController@index');
	Route::get('account/profile', 'UserAccountController@edit_profile');
	Route::post('account/profile', 'UserAccountController@update_profile');
	Route::get('account/purchaseplan', 'UserAccountController@purchase_plan');
	Route::get('account/purchase/{id}', 'UserAccountController@get_payment')->name('get_payment');
	Route::post('account/purchase', 'UserAccountController@subscribe');
	Route::get('account/billing_history', 'UserAccountController@history');
	Route::post('emailsubscribe', 'emailSubscribe@subscribe');
	Route::post('paypal_subscription', 'PaypalController@postPaymentWithpaypal')->name('paypal_subscription');
	Route::get('paypal_subscription', 'PaypalController@getPaymentStatus')->name('getPaymentStatus');
	Route::get('paypal_subscription_failed', 'PaypalController@getPaymentFailed')->name('getPaymentFailed');
    // Paypal Routes
	Route::get('paypal/cancel-subscription', 'UserAccountController@PaypalCancel')->name('cancelSubPaypal');
	Route::get('paypal/resume-subscription', 'UserAccountController@PaypalResume')->name('resumeSubPaypal');

    # Status Route
	Route::get('payment/status', 'PayuController@status');
	Route::post('payment/payu', 'PayuController@payment');

	// Bitcoin Routes
	Route::get('payment/bitcoin/status', 'BitcoinPaymentController@status');
	Route::post('payment/bitcoin', 'BitcoinPaymentController@payment');
});

// Routes With Web, Auth, Administrator Middlewares
Route::group(['middleware' => ['web', 'auth', 'is_admin', 'switch_languages']], function () {
	Route::get('admin', 'DashboardController@dashboard')->name('dashboard');
	Route::get('admin/profile', function(){
		$auth = Auth::user();
		return view('admin.profile', compact('auth'));
	});

	Route::resource('admin/menu', 'MenuController');
	Route::post('admin/menu/bulk_delete', 'MenuController@bulk_delete');
	Route::post('admin/menu/reposition', 'MenuController@reposition')->name('menu_reposition');
	
	Route::resource('admin/users', 'UsersController');
	Route::get('user/subscription/{id}', 'UsersController@change_subscription_show')->name('change_subscription_show');
	Route::post('user/subscription', 'UsersController@change_subscription')->name('change_subscription');
	Route::post('admin/users/bulk_delete', 'UsersController@bulk_delete');
	Route::resource('admin/movies', 'MovieController');
	Route::get('admin/movies/tmdb/translations', 'MovieController@tmdb_translations')->name('tmdb_movie_translate');
	Route::post('admin/movies/bulk_delete', 'MovieController@bulk_delete');
	Route::resource('admin/directors', 'DirectorController');
	Route::post('admin/directors/bulk_delete', 'DirectorController@bulk_delete');
	Route::resource('admin/actors', 'ActorController');
	Route::post('admin/actors/bulk_delete', 'ActorController@bulk_delete');

    // Genres Routes
	Route::resource('admin/genres', 'GenreController');
	Route::post('admin/genres/bulk_delete', 'GenreController@bulk_delete');
	Route::get('admin/update-to-english', 'GenreController@updateAll');

	Route::resource('admin/packages', 'PackageController');
	Route::post('admin/packages/bulk_delete', 'PackageController@bulk_delete');
	Route::resource('admin/faqs', 'FaqController');
	Route::post('admin/faqs/bulk_delete', 'FaqController@bulk_delete');
	Route::resource('admin/languages', 'LanguageController');
	Route::post('admin/languages/bulk_delete', 'LanguageController@bulk_delete');
	Route::resource('admin/settings', 'ConfigController');
	Route::get('admin/api-settings', 'ConfigController@setApiView');  
	Route::post('admin/api-settings', 'ConfigController@changeEnvKeys');  
	Route::resource('admin/coupons', 'CouponController');
	Route::post('admin/coupons/bulk_delete', 'CouponController@bulk_delete');
	Route::resource('admin/audio_language', 'AudioLanguageController');
	Route::post('admin/audio_language/bulk_delete', 'AudioLanguageController@bulk_delete');
	Route::resource('admin/home_slider', 'HomeSliderController');
	Route::post('admin/home_slider/bulk_delete', 'HomeSliderController@bulk_delete');
	Route::post('admin/home_slider/reposition', 'HomeSliderController@slide_reposition')->name('slide_reposition');
	Route::resource('admin/tvseries', 'TvSeriesController');
	Route::post('admin/tvseries/bulk_delete', 'TvSeriesController@bulk_delete');
	Route::get('admin/tvseries/tmdb/translations', 'TvSeriesController@tmdb_translations')->name('tmdb_tv_translate');
	Route::post('admin/tvseries/seasons', 'TvSeriesController@store_seasons');
	Route::patch('admin/tvseries/seasons/{id}', 'TvSeriesController@update_seasons');
	Route::delete('admin/tvseries/seasons/{id}', 'TvSeriesController@destroy_seasons');
	Route::get('admin/tvseries/seasons/{id}/episodes', 'TvSeriesController@show_episodes')->name('show_episodes');
	Route::post('admin/tvseries/seasons/episodes', 'TvSeriesController@store_episodes');
	Route::delete('admin/tvseries/seasons/episodes/{id}', 'TvSeriesController@destroy_episodes');
	Route::patch('admin/tvseries/seasons/episodes/{id}', 'TvSeriesController@update_episodes');
	Route::get('admin/report', 'ReportController@get_report');

    /////////////////////////
    // Customizable Routes //
    /////////////////////////
	Route::resource('admin/customize/landing-page', 'LandingPageController');
	Route::post('admin/customize/landing-page/bulk_delete', 'LandingPageController@bulk_delete');
	Route::post('admin/customize/landing-page/reposition', 'LandingPageController@reposition')->name('landing_page_reposition');
	Route::get('admin/customize/auth-page-customize', 'AuthCustomizeController@index');
	Route::post('admin/customize/auth-page-customize', 'AuthCustomizeController@store');

    // Site Policies Get Method
	Route::get('admin/term&con', function(){
		$config = \App\Config::whereId(1)->first();
		return view('admin.term&con', compact('config'));
	})->name('term_con');
	Route::get('admin/pri_pol', function(){
		$config = \App\Config::whereId(1)->first();
		return view('admin.pri_pol', compact('config'));
	})->name('pri_pol');
	Route::get('admin/refund_pol', function(){
		$config = \App\Config::whereId(1)->first();
		return view('admin.refund_pol', compact('config'));
	})->name('refund_pol');
	Route::get('admin/copyright', function(){
		$config = \App\Config::whereId(1)->first();
		return view('admin.copyright', compact('config'));
	})->name('copyright');

    // Site Policies Patch Method
	Route::patch('admin/term&con', function(\Illuminate\Http\Request $request){
		$config = \App\Config::whereId(1)->first();
		$input = $request->all();
		$config->update($input);
		return back()->with('updated', 'Terms & Condition has been updated');
	})->name('term&con');
	Route::patch('admin/pri_pol', function(\Illuminate\Http\Request $request){
		$config = \App\Config::whereId(1)->first();
		$input = $request->all();
		$config->update($input);
		return back()->with('updated', 'Privacy Policy has been updated');
	})->name('pri_pol');
	Route::patch('admin/refund_pol', function(\Illuminate\Http\Request $request){
		$config = \App\Config::whereId(1)->first();
		$input = $request->all();
		$config->update($input);
		return back()->with('updated', 'Refund Policy has been updated');
	})->name('refund_pol');
	Route::patch('admin/copyright', function(\Illuminate\Http\Request $request){
		$config = \App\Config::whereId(1)->first();
		$input = $request->all();
		$config->update($input);
		return back()->with('updated', 'Copyright text has been updated');
	})->name('copyright');

    /////////////////////////////////
    // Language Translation Routes //
    /////////////////////////////////
    // Header Translation Routes
	Route::get('admin/header-translations', 'HeaderTranslationController@index')->name('header-translation-index'); 
	Route::post('admin/header-translations', 'HeaderTranslationController@update');

    // Footer Translation Routes
	Route::get('admin/footer-translations', 'FooterTranslationController@index')->name('footer-translation-index'); 
	Route::post('admin/footer-translations', 'FooterTranslationController@update');
	
    // Home Page Translation Routes
	Route::get('admin/home-translations', 'HomeTranslationController@index')->name('home-translation-index'); 
	Route::post('admin/home-translations', 'HomeTranslationController@update'); 

    // Popover Detail Translation Routes
	Route::get('admin/popover-detail-translations', 'PopoverTranslationController@index')->name('popover-detail-translation-index'); 
	Route::post('admin/popover-detail-translations', 'PopoverTranslationController@update');

});

// Routes With Web, Auth And Subscriber Middlewares
Route::group(['middleware' => ['web', 'auth', 'is_subscriber', 'switch_languages']], function () {
    // User Main Movies And Shows routes Only With subscription
	Route::get('/{menu}', 'HomeController@index')->name('home');
	Route::get('movie/detail/{id}', 'PrimeDetailController@showMovie')->name('showMovie');;
	Route::get('show/detail/{id}', 'PrimeDetailController@showSeasons')->name('showSeason');;
	Route::get('home/search', 'HomeController@search');
	Route::get('video/detail/director_search/{director}', 'HomeController@director_search');
	Route::get('video/detail/actor_search/{actor}', 'HomeController@actor_search');
	Route::get('video/detail/genre_search/{genre}', 'HomeController@genre_search');
	Route::get('movies/genre/{id}', 'HomeController@movie_genre');
	Route::get('movies/language/{id}', 'HomeController@movie_language');
	Route::get('tvseries/genre/{id}', 'HomeController@tvseries_genre');
	Route::get('tvseries/language/{id}', 'HomeController@tvseries_language');

    // User Accounts routes With subscription
	Route::get('account/watchlist/shows', 'WishListController@showWishListTvShows');
	Route::get('account/watchlist/movies', 'WishListController@showWishListMovies');
	Route::delete('account/watchlist/showdestroy/{id}', 'WishListController@showdestroy');
	Route::delete('account/watchlist/moviedestroy/{id}', 'WishListController@moviedestroy');
	Route::post('addtowishlist', 'WishListController@addWishList')->name('addtowishlist');
	Route::get('cancelsubscription/{plan_id}', 'UserAccountController@cancelSub')->name('cancelSub');
	Route::get('resumesubscription/{plan_id}', 'UserAccountController@resumeSub')->name('resumeSub');

    // Api Routes For movie and Tv series
	Route::get('get_movie/{id}', 'ApiController@get_movie')->name('get_movie');
	Route::get('get_season/{id}', 'ApiController@get_season')->name('get_season');
	Route::post('get-video-data', 'ApiController@get_video_data')->name('get_video_data');
	Route::resource('admin/seo', 'SeoController');

	Route::resource('admin/plan', 'PlanController');
	Route::post('admin/plan/bulk_delete', 'PlanController@bulk_delete');
	Route::post('admin/plan/change_subscription', 'PlanController@change_subscription');

	// Ajax
	Route::post('store-time', 'HomeController@storeTime')->name('store_time');
	Route::post('store-audio-track', 'HomeController@storeAudioTrack')->name('store_audio_track');
});

