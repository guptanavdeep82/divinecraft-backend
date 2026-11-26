@extends('admin.layout')
@section('title', 'Report')
@section('content')
  <h1>Payment report</h1>
  <form class="panel" method="get" style="margin-bottom:18px">
    <div class="split">
      <label>From <input type="date" name="from" value="{{ $from }}"></label>
      <label>To <input type="date" name="to" value="{{ $to }}"></label>
    </div>
    <button class="btn" type="submit">Show</button>
  </form>
  <div class="cards">
    <article class="card"><span>Captured revenue</span><b>₹{{ number_format($revenue) }}</b></article>
    <article class="card"><span>Paid orders</span><b>{{ $paidCount }}</b></article>
    <article class="card"><span>Pending</span><b>{{ $pending }}</b></article>
    <article class="card"><span>Failed orders</span><b>{{ $failed }}</b></article>
    <article class="card"><span>Payment attempts</span><b>{{ $attempts }}</b></article>
    <article class="card"><span>Captured payments</span><b>{{ $captured }}</b></article>
  </div>
  <h2 style="margin-top:28px">Orders in this range</h2>
  <table>
    <thead><tr><th>Order</th><th>Customer</th><th>Amount</th><th>Status</th></tr></thead>
    <tbody>
      @forelse ($recent as $order)
        <tr>
          <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->number }}</a></td>
          <td>{{ $order->customer_name }}</td>
          <td>{{ $order->rupees() }}</td>
          <td>{{ ucfirst($order->status) }}</td>
        </tr>
      @empty
        <tr><td colspan="4">Nothing in this range.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
