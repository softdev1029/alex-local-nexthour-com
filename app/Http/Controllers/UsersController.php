<?php

namespace App\Http\Controllers;

use App\User;
use App\Package;
use App\PaypalSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth_id = Auth::id();
        $users = User::where('id', '!=', $auth_id)->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
          'name' => 'required',
          'image' => 'nullable|image|mimes:jpeg,png,jpg',
          'email' => 'required|email|unique:users',
          'password' => 'required',
          'confirm_password' => 'required|same:password'
        ]);

        $input = $request->except('confirm_password');

        $input['password'] = bcrypt($request->password);

        if(!isset($input['is_admin']))
        {
            $input['is_admin'] = 0;
        }

        if ($file = $request->file('image')) {
          $name = 'user_'.$file->getClientOriginalName();
          $file->move('images/users', $name);
          $input['image'] = $name;
        }

        User::create($input);
        return redirect('admin/users')->with('added', 'User has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
          'name' => 'required',
          'image' => 'nullable|image|mimes:jpeg,png,jpg',
          'email' => 'required|email',
          'role' => 'nullable',
          'confirm_password' => 'same:password'
        ]);

        $input = $request->except('confirm_password');

        if ($request->password !== '' || $request->password != null)
        {
          $input['password'] = bcrypt($request->password);
        }

        if(!isset($input['is_admin']))
        {
            $input['is_admin'] = 0;
        }

        if ($file = $request->file('image')) {
          $name = 'user_'.$file->getClientOriginalName();
          if($user->image != '') {
            unlink(public_path() . '/images/users/' . $user->image);
          }
          $file->move('images/users', $name);
          $input['image'] = $name;
        }

        $user->update($input);
        return redirect('admin/users')->with('updated', 'User has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->image) {
          unlink(public_path() . 'images/users/' . $user->image);
        }

        $user->delete();
        return back()->with('deleted', 'User has been deleted');
    }

    public function bulk_delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'checked' => 'required',
        ]);

        if ($validator->fails()) {

            return back()->with('deleted', 'Please select one of them to delete');
        }

        foreach ($request->checked as $checked) {

            $user = User::findOrFail($checked);

            User::destroy($checked);
        }

        return back()->with('deleted', 'Users has been deleted');   
    }

    public function change_subscription_show($id)
    {
        $user = User::findOrFail($id);

        $plans = Package::all();
        $plan_list = Package::pluck('name', 'id')->all();

        $user_stripe_plan = null;
        $last_payment = null;

        if ($user->stripe_id != null) {
            foreach ($plans as $plan) {
                if ($user->subscribed($plan->plan_id)) {
                    $user_stripe_plan = $plan;
                }
            }    
        }

        if (isset($user->paypal_subscriptions) && count($user->paypal_subscriptions) > 0) {
            //Check Paypal Subscription of user
            $last_payment = $user->paypal_subscriptions->last();
        }

        return view('admin.users.change_sub', compact('user', 'user_stripe_plan', 'last_payment', 'plan_list'));
    }

    public function change_subscription(Request $request)
    {
        $request->validate([
            'plan_id' => 'required',
            'user_id' => 'required'
        ]);

        $user = User::findOrFail($request->user_id);
        $change_plan = Package::findOrFail($request->plan_id);

        if ($request->user_stripe_plan_id != null) {

            $user_stripe_plan = Package::findOrFail($request->user_stripe_plan_id);
            $user->subscription($user_stripe_plan->plan_id)->swap($change_plan->plan_id);
            return back()->with('added', 'User subscription has been changed!');   

        } else if ($request->last_payment_id != null) {

            $last_payment = PaypalSubscription::findOrFail($request->last_payment_id);
            $current_date = Carbon::now();
            $end_date = null;

            if ($change_plan->interval == 'month') {
                $end_date = Carbon::now()->addMonths($change_plan->interval_count);
            } else if ($change_plan->interval = 'year') {
                $end_date = Carbon::now()->addYears($change_plan->interval_count);
            } else if ($change_plan->interval == 'week') {
                $end_date = Carbon::now()->addWeeks($change_plan->interval_count);
            } else if ($change_plan->interval == 'day') {
                $end_date = Carbon::now()->addDays($change_plan->interval_count);
            }

            $last_payment->package_id = $change_plan->id;
            $last_payment->price = $change_plan->amount;
            $last_payment->status = 1;
            $last_payment->method = 'by Admin';
            $last_payment->subscription_from = $current_date;
            $last_payment->subscription_to = $end_date;
            $last_payment->save();

            return back()->with('added', 'User subscription has been changed!');   
            
        } else if ($request->user_stripe_plan_id == null && $request->last_payment_id == null) {

            $current_date = Carbon::now();
            $end_date = null;

            if ($change_plan->interval == 'month') {
                $end_date = Carbon::now()->addMonths($change_plan->interval_count);
            } else if ($change_plan->interval = 'year') {
                $end_date = Carbon::now()->addYears($change_plan->interval_count);
            } else if ($change_plan->interval == 'week') {
                $end_date = Carbon::now()->addWeeks($change_plan->interval_count);
            } else if ($change_plan->interval == 'day') {
                $end_date = Carbon::now()->addDays($change_plan->interval_count);
            }

            $created_subscription = PaypalSubscription::create([
                'user_id' => $user->id,
                'payment_id' => 'by admin',
                'user_name' => $user->name,
                'package_id' => $change_plan->id,
                'price' => $change_plan->amount,
                'status' => 1,
                'method' => 'by Admin',
                'subscription_from' => $current_date,
                'subscription_to' => $end_date
            ]);

            return back()->with('added', 'User subscription has been changed!');   
        }
        return back()->with('error', 'Some issue to change this user subscription');   

    }

}
