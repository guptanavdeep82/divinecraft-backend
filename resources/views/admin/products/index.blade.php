@extends('admin.layout')
@section('title', 'Poshaks')
@section('content')
  <div class="row">
    <h1>Poshaks</h1>
    <a class="btn" href="{{ route('admin.products.create') }}">Add poshak</a>
  </div>
  <table>
    <thead><tr><th></th><th>Name</th><th>Price</th><th>Category</th><th></th></tr></thead>
    <tbody>
      @foreach ($products as $product)
        <tr>
          <td><img class="thumb" src="{{ env('FRONTEND_URL', 'http://localhost:3010') }}{{ $product->image }}" alt=""></td>
          <td>{{ $product->name }}<br><small>{{ $product->is_active ? 'Live' : 'Hidden' }}</small></td>
          <td>₹{{ number_format($product->price) }}</td>
          <td>{{ $product->category?->name }}</td>
          <td>
            <a href="{{ route('admin.products.edit', $product) }}">Edit</a>
            <form method="post" action="{{ route('admin.products.destroy', $product) }}" style="display:inline" onsubmit="return confirm('Remove this poshak?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:none;border:0;color:#9e1b32;cursor:pointer">Remove</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
