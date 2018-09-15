<?php

namespace App\Http\Middleware;

use App\Package;
use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\Customer;
use Stripe\Stripe;

class IsSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {   
        if (Auth::check()) {
            
            $auth = Auth::user();

            if ($auth->is_admin == 1) {

                return $next($request);
                
            }

            if ($auth->stripe_id != null) {
                //Set your secret key: remember to change this to your live secret key in production
                Stripe::setApiKey(env('STRIPE_SECRET'));

                $plans = Package::all();

                foreach ($plans as $plan) {

                    if ($auth->subscribed($plan->plan_id) || $auth->is_admin == 1) {

                        return $next($request);
                        
                    } else {

                        return redirect('account/purchaseplan');

                    }

                }

            } else if (isset($auth->paypal_subscriptions) && count($auth->paypal_subscriptions) > 0) {  
                
                //Check Paypal Subscription of user
                $last_payment = $auth->paypal_subscriptions->last();

                if (isset($last_payment) && $last_payment->status == 1) {
                    //check last date to current date
                    $current_date = Carbon::now();
                    if (date($current_date) <= date($last_payment->subscription_to)) {
                        return $next($request);
                    } else {
                        $last_payment->status = 0;
                        $last_payment->save();
                        return redirect('account/purchaseplan')->with('deleted', 'Your subscription has been expired!');
                    }
                }

                return redirect('account/purchaseplan')->with('deleted', 'Your subscription has been expired');

            } else {

                return redirect('account/purchaseplan')->with('deleted', 'You have no subscription please subscribe');

            }

        } else {

            return redirect('login')->with('updated', 'Please login first!');

        }
    }
}
