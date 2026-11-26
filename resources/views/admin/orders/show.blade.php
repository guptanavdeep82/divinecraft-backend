@extends('admin.layout')
@section('title', $order->number)
@section('content')
  <div class="row">
    <h1>{{ $order->number }}</h1>
    <a class="btn ghost" href="{{ route('admin.orders') }}">All orders</a>
  </div>
  <div class="cards" style="margin-bottom:18px">
    <article class="card"><span>Status</span><b>{{ ucfirst($order->status) }}</b></article>
    <article class="card"><span>Amount</span><b>{{ $order->rupees() }}</b></article>
    <article class="card"><span>Paid</span><b style="font-size:20px">{{ $order->paid_at?->format('d M Y, H:i') ?? '—' }}</b></article>
  </div>
  <table>
    <tbody>
      <tr><th>Customer</th><td>{{ $order->customer_name }} · {{ $order->phone }}{{ $order->email ? ' · '.$order->email : '' }}</td></tr>
      <tr><th>Deliver to</th><td>{{ $order->address ? $order->address.', '.$order->city.', '.$order->state.' '.$order->pincode : '—' }}</td></tr>
      <tr><th>Poshak</th><td>
        @if ($order->items->isNotEmpty())
          @foreach ($order->items as $item)
            <div>{{ $item->name }} × {{ $item->qty }} · ₹{{ number_format($item->price) }}</div>
          @endforeach
        @else
          {{ $order->product?->name ?? '—' }}
        @endif
      </td></tr>
      <tr><th>Personalise</th><td>{{ $order->personalise ? 'Yes' : 'No' }}{{ $order->embroidery_name ? ' · '.$order->embroidery_name : '' }}{{ $order->script ? ' · '.$order->script : '' }}{{ $order->zari ? ' · '.$order->zari : '' }}</td></tr>
      <tr><th>Note</th><td>{{ $order->note ?: '—' }}</td></tr>
      <tr><th>Razorpay order</th><td>{{ $order->razorpay_order_id ?: '—' }}</td></tr>
      <tr><th>Shiprocket</th><td>{{ $order->shiprocket_order_id ?: 'Not booked' }}{{ $order->courier ? ' · '.$order->courier : '' }}{{ $order->awb ? ' · AWB '.$order->awb : '' }}</td></tr>
      <tr><th>Tracking</th><td>{{ $order->tracking_status ?: '—' }}</td></tr>
    </tbody>
  </table>
  <div class="row" style="margin-top:18px">
    <form method="post" action="{{ route('admin.orders.ship', $order) }}">
      @csrf
      <button class="btn" type="submit">Book on Shiprocket</button>
    </form>
    <form method="post" action="{{ route('admin.orders.track', $order) }}">
      @csrf
      <button class="btn ghost" type="submit">Refresh tracking</button>
    </form>
  </div>
  <h2 style="margin-top:28px">Payments</h2>
  <table>
    <thead><tr><th>When</th><th>Payment id</th><th>Status</th><th>Error</th></tr></thead>
    <tbody>
      @forelse ($order->payments as $payment)
        <tr>
          <td>{{ $payment->created_at->format('d M, H:i') }}</td>
          <td>{{ $payment->razorpay_payment_id ?: '—' }}</td>
          <td>{{ ucfirst($payment->status) }}</td>
          <td>{{ $payment->error ?: '—' }}</td>
        </tr>
      @empty
        <tr><td colspan="4">No payment attempts.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
