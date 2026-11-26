@extends('admin.layout')
@section('title', $banner->exists ? 'Edit banner' : 'Add banner')
@section('content')
  <h1>{{ $banner->exists ? 'Edit banner' : 'Add banner' }}</h1>
  @if ($errors->any()) <p class="error">{{ $errors->first() }}</p> @endif
  <form class="panel" method="post" enctype="multipart/form-data" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}">
    @csrf
    @if ($banner->exists) @method('PUT') @endif
    <label>Eyebrow <input name="eyebrow" value="{{ old('eyebrow', $banner->eyebrow) }}"></label>
    <label>Title <input name="title" value="{{ old('title', $banner->title) }}" required></label>
    <label>Subtitle <textarea name="subtitle" rows="3">{{ old('subtitle', $banner->subtitle) }}</textarea></label>
    <div class="split">
      <label>Button <input name="cta" value="{{ old('cta', $banner->cta) }}"></label>
      <label>Button link <input name="cta_href" value="{{ old('cta_href', $banner->cta_href) }}"></label>
    </div>
    <div class="split">
      <label>Second button <input name="cta2" value="{{ old('cta2', $banner->cta2) }}"></label>
      <label>Note <input name="note" value="{{ old('note', $banner->note) }}"></label>
    </div>
    <label>Image path <input name="image" value="{{ old('image', $banner->image) }}" placeholder="/images/your-photo.jpg"></label>
    <label>Or upload <input type="file" name="photo" accept="image/*"></label>
    <div class="split">
      <label>Image alt <input name="image_alt" value="{{ old('image_alt', $banner->image_alt) }}"></label>
      <label>Small circle image <input name="image2" value="{{ old('image2', $banner->image2) }}"></label>
    </div>
    <div class="split">
      <label>Chip label <input name="chip_top" value="{{ old('chip_top', $banner->chip_top) }}"></label>
      <label>Chip text <input name="chip" value="{{ old('chip', $banner->chip) }}"></label>
    </div>
    <div class="split">
      <label>Background <input name="background" value="{{ old('background', $banner->background) }}"></label>
      <label>Sort <input type="number" name="sort" min="0" value="{{ old('sort', $banner->sort ?? 0) }}"></label>
    </div>
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true))> Show on the homepage</label>
    <button class="btn" type="submit">Save banner</button>
  </form>
@endsection
