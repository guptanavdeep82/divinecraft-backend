@extends('admin.layout')
@section('title', 'Banners')
@section('content')
  <div class="row">
    <h1>Banners</h1>
    <a class="btn" href="{{ route('admin.banners.create') }}">Add banner</a>
  </div>
  <table>
    <thead><tr><th>Title</th><th>Order</th><th></th></tr></thead>
    <tbody>
      @foreach ($banners as $banner)
        <tr>
          <td>{{ $banner->title }}<br><small>{{ $banner->is_active ? 'Live' : 'Hidden' }}</small></td>
          <td>{{ $banner->sort }}</td>
          <td>
            <a href="{{ route('admin.banners.edit', $banner) }}">Edit</a>
            <form method="post" action="{{ route('admin.banners.destroy', $banner) }}" style="display:inline" onsubmit="return confirm('Remove this banner?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:none;border:0;color:#9e1b32;cursor:pointer">Remove</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
