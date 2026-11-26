@extends('admin.layout')
@section('title', 'Orders')
@section('content')
  <div class="row">
    <h1>Orders</h1>
    <form method="get">
      <select name="status" onchange="this.form.submit()">
        <option value="">All</option>
        @foreach (['pending', 'paid', 'failed'] as $option)
          <option value="{{ $option }}" @selected($status === $option)>{{ ucfirst($option) }}</option>
        @endforeach
      </select>
    </form>
  </div>
  <table>
    <thead><tr><th></th><th>Order</th><th>Customer</th><th>Poshak</th><th>Amount</th><th>Status</th></tr></thead>
    <tbody>
      @forelse ($orders as $order)
        <tr>
          @php $shot = $order->items->first()?->product?->image ?: $order->product?->image; @endphp
          <td>@if ($shot)<img class="thumb" src="{{ \App\Support\Media::url($shot) }}" alt="">@endif</td>
          <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->number }}</a><br><small>{{ $order->created_at->format('d M, H:i') }}</small></td>
          <td>{{ $order->customer_name }}<br>{{ $order->phone }}</td>
          <td>{{ $order->poshakLabel() }}</td>
          <td>{{ $order->rupees() }}</td>
          <td>{{ ucfirst($order->status) }}</td>
        </tr>
      @empty
        <tr><td colspan="6">No orders yet.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
