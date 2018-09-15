<?php

namespace App\Http\Controllers;

use App\Config;
use App\Menu;
use App\Package;
use App\PaypalSubscription;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Tzsk\Payu\Facade\Payment;

use Log;

class BitcoinPaymentController extends Controller
{
    public function payment(Request $request)
    {
        
        if (empty($request->plan_id))
            abort(404);

    	$plan = Package::findOrFail($request->plan_id);

        $bitcoin_payment = Config::findOrFail(1)->bitcoin_payment;
        if (!empty($bitcoin_payment)) {
            $data = '';

            $options = array(
              'http' => array(
                  'header'  => 'Authorization: Bearer '.env('BITCOIN_API_KEY'),
                  'method'  => 'POST',
                  'content' => $data
              ) 
            );

          // Generate new address for this invoice
          $context = stream_context_create($options);
          $contents = file_get_contents('https://www.blockonomics.co/api/new_address', false, $context);
          $new_address = json_decode($contents);

          // Getting price
          $options = array( 'http' => array( 'method'  => 'GET') );  
          $context = stream_context_create($options);
          $contents = file_get_contents('https://www.blockonomics.co/api/price?currency=' . $plan->currency, false, $context);
          $price = json_decode($contents);

          // Total Cart value in bits
          $bits = intval(1.0e8 * $plan->amount / $price->price);

          $auth = Auth::user();
          $auth->bitcoin_paid = 0;
          $auth->bitcoin_plan_id = $plan->id;
          $auth->bitcoin_address = $new_address->address;
          $auth->save();

          view()->share([
            'bits' => $bits,
            'new_address' => $new_address,
            'bitcoin_paid' => true
          ]);
      }

      return view('bitcoin', compact('plan'));
    }

    public function status(Request $request)
    {
        $txid = $request->input('txid');
        $value = $request->input('value');
        $status = $request->input('status');
        $addr = $request->input('addr');
        $secret = $request->input('secret');

        if (env('BITCOIN_SECRET_KEY') != $secret) {
            Log::error("BitcoinPaymentController@status: Invalid secret key, addr:$addr, secret:$secret");
            echo "BitcoinPaymentController@status: Invalid secret key, addr:$addr, secret:$secret";
            return;
        }

        if ($status != 2) {
            Log::error("BitcoinPaymentController@status: Unconfirmed transaction, addr:$addr, secret:$secret");
            echo "BitcoinPaymentController@status: Unconfirmed transaction, addr:$addr, secret:$secret";
            return;
        }

        $user = User::where('bitcoin_address', $addr)->first();

        if (!$user) {
            Log::error("BitcoinPaymentController@status: Invalid bitcoin address, addr:$addr, secret:$secret");
            echo "BitcoinPaymentController@status: Invalid bitcoin address, addr:$addr, secret:$secret";
            return;
        }

        $plan = Package::findOrFail($user->bitcoin_plan_id);

        if (!$plan) {
            Log::error("BitcoinPaymentController@status: Invalid plan, addr:$addr, secret:$secret, user:$user->id");
            echo "BitcoinPaymentController@status: Invalid plan, addr:$addr, secret:$secret, user:$user->id";
            return;
        }
    	$menus = Menu::all();
    	$user_email = $user->email;
        $com_email = Config::findOrFail(1)->w_email;

		$current_date = Carbon::now();
        $end_date = null;

        if ($plan->interval == 'month') {
            $end_date = Carbon::now()->addMonths($plan->interval_count);
        } else if ($plan->interval = 'year') {
            $end_date = Carbon::now()->addYears($plan->interval_count);
        } else if ($plan->interval == 'week') {
            $end_date = Carbon::now()->addWeeks($plan->interval_count);
        } else if ($plan->interval == 'day') {
            $end_date = Carbon::now()->addDays($plan->interval_count);
        }

        $auth = $user;

        $created_subscription = PaypalSubscription::create([
            'user_id' => $auth->id,
            'payment_id' => $txid,
            'user_name' => $auth->name,
            'package_id' => $plan->id,
            'price' => $plan->amount,
            'status' => 1,
            'method' => 'bitcoin',
            'subscription_from' => $current_date,
            'subscription_to' => $end_date
        ]);

        if ($created_subscription) {
            $user->bitcoin_paid = 1;
            $user->bitcoin_address = '';
            $user->save();

            Mail::send('user.invoice', ['paypal_sub' => $created_subscription, 'invoice' => null], function($message) use ($com_email, $user_email) {
                $message->from($com_email)->to($user_email)->subject('Invoice');
            });
        }
    }
}
