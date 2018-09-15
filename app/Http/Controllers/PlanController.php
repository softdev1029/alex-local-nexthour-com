<?php

namespace App\Http\Controllers;

use App\Plan;
use App\User;
use App\Package;
use App\PaypalSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_all = User::all();
        $plans = Package::all();
        return view('admin.plan.index', compact('plans','user_all'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(plan $plan)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.users_plan.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $plan = Plan::findOrFail($id);

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
          if($plan->image != '') {
            unlink(public_path() . '/images/users/' . $plan->image);
          }
          $file->move('images/plan', $name);
          $input['image'] = $name;
        }

        $plan->update($input);
        return redirect('admin/plan')->with('updated', 'User has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return back()->with('deleted', 'plan has been deleted');
    }

    
    
    
    }

