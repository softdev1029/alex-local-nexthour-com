@extends('layouts.admin')
@section('content')
  <div class="content-main-block mrg-t-40">
    <div class="admin-create-btn-block">
    </div>
    <div class="content-block box-body">
      <table id="full_detail_table" class="table table-hover">
        <thead>
          <tr class="table-heading-row">
            <th>
              #
            </th>
            <th>Name</th>
            <th>Email</th>
            <th>Plan</th>
            <th>Actions</th>
          </tr>
        </thead>
        @if ($user_all)
          @php
            $no = 1;
          @endphp
          <tbody>
            @foreach ($user_all as $user)
              @php
                $user_stripe_plan = null;
                $paypal_plan = null;
                if ($user->stripe_id != null) {
                    foreach ($plans as $plan) {
                        if ($user->subscribed($plan->plan_id)) {
                            $user_stripe_plan = $plan;
                        }
                    }    
                }
                if (isset($user->paypal_subscriptions) && count($user->paypal_subscriptions) > 0) {
                    $paypal_plan = $user->paypal_subscriptions->last();
                }
              @endphp
              @if($user_stripe_plan!=null || $paypal_plan!=null)
                <tr>
                  <td>
                    {{$no}}
                    @php 
                      $no++
                    @endphp
                  </td>
                  <td>{{$user->name}}</td>
                  <td>{{$user->email}}</td>
                  <td>{{$user_stripe_plan != null ? $user_stripe_plan->name : ($paypal_plan != null ? $paypal_plan->plan->name : 'No Plans')}}</td>
                  <td>
                    <div class="admin-table-action-block">
                      <a href="{{route('change_subscription_show', $user->id)}}" data-toggle="tooltip" data-original-title="Change Subscription" class="btn-default btn-floating"><i class="material-icons">compare_arrows</i></a>
                    </div>
                  </td>
                </tr>
              @endif
            @endforeach
          </tbody>
        @endif  
      </table>
    </div>
  </div>
@endsection
@section('custom-script')
  <script>
    $(function(){
      $('#checkboxAll').on('change', function(){
        if($(this).prop("checked") == true){
          $('.material-checkbox-input').attr('checked', true);
        }
        else if($(this).prop("checked") == false){
          $('.material-checkbox-input').attr('checked', false);
        }
      });
    });
  </script>
@endsection