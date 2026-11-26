@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
  <h1>Dashboard</h1>
  <div class="cards">
    <article class="card"><span>Poshaks</span><b>{{ $products }}</b></article>
    <article class="card"><span>Live banners</span><b>{{ $banners }}</b></article>
    <article class="card"><span>New enquiries</span><b>{{ $enquiries }}</b></article>
    <article class="card"><span>Paid revenue</span><b>₹{{ number_format($revenue) }}</b></article>
    <article class="card"><span>Paid orders</span><b>{{ $paidOrders }}</b></article>
    <article class="card"><span>Pending payment</span><b>{{ $pendingOrders }}</b></article>
    <article class="card"><span>Registered users</span><b>{{ $customers }}</b></article>
    <article class="card"><span>Journal stories</span><b>{{ $blogs }}</b></article>
  </div>
@endsection
