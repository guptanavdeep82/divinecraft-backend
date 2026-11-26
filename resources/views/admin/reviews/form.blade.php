@extends('admin.layout')
@section('title', $review->exists ? 'Edit review' : 'New review')
@section('content')
  <div class="row">
    <h1>{{ $review->exists ? 'Edit review' : 'New review' }}</h1>
    <a class="btn ghost" href="{{ route('admin.reviews') }}">All reviews</a>
  </div>
  <form class="panel" method="post" action="{{ $review->exists ? route('admin.reviews.update', $review) : route('admin.reviews.store') }}">
    @csrf
    @if ($review->exists) @method('PUT') @endif
    <div class="split">
      <label>Name <input name="name" value="{{ old('name', $review->name) }}" required></label>
      <label>City <input name="city" value="{{ old('city', $review->city) }}" placeholder="Jaipur"></label>
    </div>
    <label>Quote <textarea name="quote" rows="4" required>{{ old('quote', $review->quote) }}</textarea></label>
    <div class="split">
      <label>Rating
        <select name="rating">
          @for ($star = 5; $star >= 1; $star--)
            <option value="{{ $star }}" @selected((int) old('rating', $review->rating) === $star)>{{ $star }}</option>
          @endfor
        </select>
      </label>
      <label>Sort <input type="number" name="sort" value="{{ old('sort', $review->sort ?? 0) }}" min="0"></label>
    </div>
    <label class="checks"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $review->exists ? $review->is_active : true))> Show on the homepage</label>
    @if ($errors->any())
      <p class="error">{{ $errors->first() }}</p>
    @endif
    <button class="btn" type="submit">Save review</button>
  </form>
@endsection
