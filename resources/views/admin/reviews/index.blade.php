@extends('admin.layout')
@section('title', 'Reviews')
@section('content')
  <div class="row">
    <h1>Reviews</h1>
    <a class="btn" href="{{ route('admin.reviews.create') }}">Add review</a>
  </div>
  <table>
    <thead><tr><th>Name</th><th>Quote</th><th>Rating</th><th></th></tr></thead>
    <tbody>
      @forelse ($reviews as $review)
        <tr>
          <td>{{ $review->name }}<br><small>{{ $review->city }} · {{ $review->is_active ? 'Live' : 'Hidden' }}</small></td>
          <td>{{ $review->quote }}</td>
          <td>{{ $review->rating }}/5</td>
          <td>
            <a href="{{ route('admin.reviews.edit', $review) }}">Edit</a>
            <form method="post" action="{{ route('admin.reviews.destroy', $review) }}" style="display:inline" onsubmit="return confirm('Remove this review?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:none;border:0;color:#9e1b32;cursor:pointer">Remove</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4">No reviews yet.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
