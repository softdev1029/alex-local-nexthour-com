<?php

namespace App\Providers;

use App\AuthCustomize;
use App\Config;
use App\FooterTranslation;
use App\HeaderTranslation;
use App\HomeTranslation;
use App\Language;
use App\Menu;
use App\Package;
use App\seo;
use App\Button;
use App\PopoverTranslation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view)
        {
            $auth_customize = AuthCustomize::first();
            $nav_menus = Menu::orderBy('position', 'asc')->get();
            $header_translations = HeaderTranslation::all(); 
            $footer_translations = FooterTranslation::all(); 
            $home_translations = HomeTranslation::all(); 
            $popover_translations = PopoverTranslation::all(); 
            $languages = Language::all(); 
            $auth = Auth::user();
            $com_name = Config::findOrFail(1)->w_name;
            $com_add = Config::findOrFail(1)->invoice_add;
            $com_email = Config::findOrFail(1)->w_email;
            $currency_code = Config::findOrFail(1)->currency_code;
            $currency_symbol = Config::findOrFail(1)->currency_symbol;
            $term_con = Config::findOrFail(1)->terms_condition;
            $pri_pol = Config::findOrFail(1)->privacy_pol;
            $refund_pol = Config::findOrFail(1)->refund_pol;
            $copyright = Config::findOrFail(1)->copyright;
            $logo = Config::findOrFail(1)->logo;
            $w_title = Config::findOrFail(1)->title;
            $favicon = Config::findOrFail(1)->favicon;
            $prime_main_slider = Config::findOrFail(1)->prime_main_slider;
            $prime_genre_slider = Config::findOrFail(1)->prime_genre_slider;
            $prime_footer = Config::findOrFail(1)->prime_footer;
            $prime_movie_single = Config::findOrFail(1)->prime_movie_single;
            $stripe_payment = Config::findOrFail(1)->stripe_payment;
            $paypal_payment = Config::findOrFail(1)->paypal_payment;
            $payu_payment = Config::findOrFail(1)->payu_payment;
            $bitcoin_payment = Config::findOrFail(1)->bitcoin_payment;
            $preloader = Config::findOrFail(1)->preloader;
            $inspect = Button::findOrFail(1)->inspect;
            $rightclick = Button::findOrFail(1)->rightclick;
            $goto = Button::findOrFail(1)->goto;
            $color = Button::findOrFail(1)->color;
            $fb = seo::findOrFail(1)->fb;
            $google = seo::findOrFail(1)->google;
            $description = seo::findOrFail(1)->description;
            $keyword = seo::findOrFail(1)->keyword;
            $omdbApiKey = env('OMDB_API_KEY');
            $tmdbApiKey = env('TMDB_API_KEY');

            $view->with(['color' => $color,'description' => $description,'keyword' => $keyword,'goto' => $goto,
                'fb' => $fb,'google' => $google,'rightclick' => $rightclick,'inspect'=>$inspect, 
                'company_name' => $com_name, 'w_email' => $com_email, 'invoice_add' => $com_add, 
                'auth' => $auth, 'prime_main_slider' => $prime_main_slider, 'prime_genre_slider' => $prime_genre_slider, 
                'prime_footer' => $prime_footer, 'prime_movie_single' => $prime_movie_single, 'omdbapikey'=>$omdbApiKey,
                'tmdbapikey'=>$tmdbApiKey,'currency_code' => $currency_code, 'currency_symbol' => $currency_symbol, 
                'logo'=> $logo, 'favicon'=> $favicon, 'term_con' => $term_con, 'pri_pol' => $pri_pol,
                 'refund_pol' => $refund_pol, 'copyright' => $copyright, 'w_title' => $w_title, 'languages' => $languages, 
                 'header_translations' => $header_translations, 'footer_translations' => $footer_translations, 
                 'home_translations' => $home_translations, 'popover_translations' => $popover_translations, 
                 'nav_menus' => $nav_menus, 'stripe_payment' => $stripe_payment, 'paypal_payment' => $paypal_payment, 
                 'payu_payment' => $payu_payment, 
                 'bitcoin_payment' => $bitcoin_payment,
                  'auth_customize' => $auth_customize, 'preloader' => $preloader]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
