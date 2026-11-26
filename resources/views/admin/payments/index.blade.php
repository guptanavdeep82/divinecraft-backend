@extends('admin.layout')
@section('title', 'Payments')
@section('content')
  <h1>Payments</h1>
  <table>
    <thead><tr><th>When</th><th>Order</th><th>Razorpay payment</th><th>Amount</th><th>Status</th></tr></thead>
    <tbody>
      @forelse ($payments as $payment)
        <tr>
          <td>{{ $payment->created_at->format('d M, H:i') }}</td>
          <td><a href="{{ route('admin.orders.show', $payment->order) }}">{{ $payment->order->number }}</a></td>
          <td>{{ $payment->razorpay_payment_id ?: $payment->razorpay_order_id ?: '—' }}</td>
          <td>₹{{ number_format($payment->amount) }}</td>
          <td>{{ ucfirst($payment->status) }}{{ $payment->error ? ' · '.$payment->error : '' }}</td>
        </tr>
      @empty
        <tr><td colspan="5">No payments yet.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
