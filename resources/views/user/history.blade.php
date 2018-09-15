@extends('layouts.theme')

@section('main-wrapper')
  <!-- main wrapper -->
  <section id="main-wrapper" class="main-wrapper home-page user-account-section">
    <div class="container-fluid">
      <h4 class="heading">Invoice Details</h4>
      <ul class="bradcump">
        <li><a href="{{url('account')}}">Dashboard</a></li>
        <li>/</li>
        <li>Invoice Details</li>
      </ul>
      <div class="panel-setting-main-block billing-history-main-block">
        <div class="container">
          <h4 class="plan-dtl-heading">Billing History</h4>
          <div class="billing-history-block table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Package</th>
                  <th>Service Period</th>
                  <th>Payment Method</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @if(isset($invoices) && $invoices != null && count($invoices->data) > 0)
                  @foreach($invoices->data as $invoice)
                    <tr>
                      <td>{{date("d/m/Y", $invoice->date)}}</td>
                      <td>{{$invoice->lines->data[0]->plan->id}}</td>
                      <td>{{date("d/m/y", $invoice->lines->data[0]->period->start)}} to {{date("d/m/y", $invoice->lines->data[0]->period->end)}}</td>
                      <td>Stripe</td>
                      <td><i class="{{$currency_symbol}}"></i>{{$invoice->total/100}}</td>
                    </tr>
                  @endforeach
                @endif
                @if (isset($paypal_subscriptions) && count($paypal_subscriptions) > 0)
                  @foreach ($paypal_subscriptions as $item)
                    @php
                      $from = Carbon\Carbon::parse($item->subscription_from);
                      $from = $from->toDateString();
                      $to = Carbon\Carbon::parse($item->subscription_to);
                      $to = $to->toDateString();
                    @endphp
                    <tr>
                      <td>{{$item->created_at->toDateString()}}</td>
                      <td>{{$item->plan ? $item->plan->name : 'N/A'}}</td>
                      <td>{{$from}} to {{$to}}</td>
                      <td>{{ucfirst($item->method)}}</td>
                      <td><i class="{{$currency_symbol}}"></i> {{$item->price}}</td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end main wrapper -->
@endsection