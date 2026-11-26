@extends('admin.layout')
@section('title', $product->exists ? 'Edit poshak' : 'Add poshak')
@section('content')
  <h1>{{ $product->exists ? 'Edit poshak' : 'Add poshak' }}</h1>
  @if ($errors->any())
    <p class="error">{{ $errors->first() }}</p>
  @endif
  <form class="panel" method="post" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if ($product->exists) @method('PUT') @endif
    <label>Name <input name="name" value="{{ old('name', $product->name) }}" required></label>
    <div class="split">
      <label>Slug <input name="slug" value="{{ old('slug', $product->slug) }}" placeholder="Filled from the name if empty"></label>
      <label>Price in rupees <input type="number" name="price" min="0" value="{{ old('price', $product->price) }}" required></label>
    </div>
    <div class="split">
      <label>Category
        <select name="category_id">
          <option value="">None</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
          @endforeach
        </select>
      </label>
      <label>Tier line <input name="tier" value="{{ old('tier', $product->tier) }}" placeholder="Utsav · Zardozi, stones"></label>
    </div>
    <div class="split">
      <label>Badge <input name="badge" value="{{ old('badge', $product->badge) }}" placeholder="Bestseller"></label>
      <label>Badge colour <input name="badge_color" value="{{ old('badge_color', $product->badge_color) }}"></label>
    </div>
    <div class="split">
      <label>Arrives by <input name="eta" value="{{ old('eta', $product->eta) }}" placeholder="4 Dec"></label>
      <label>Sort <input type="number" name="sort" min="0" value="{{ old('sort', $product->sort ?? 0) }}"></label>
    </div>
    <div class="split">
      <label>Deity <input name="deity" value="{{ old('deity', $product->deity) }}"></label>
      <label>Colour <input name="colour" value="{{ old('colour', $product->colour) }}" placeholder="Peela"></label>
    </div>
    @include('admin.partials.photo', ['name' => 'image', 'value' => $product->image, 'label' => 'Image path', 'fileName' => 'photo'])
    <label>Description <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea></label>
    <div class="checks">
      @foreach ($groups as $key => $label)
        <label><input type="checkbox" name="groups[]" value="{{ $key }}" @checked(in_array($key, old('groups', $selected)))> {{ $label }}</label>
      @endforeach
    </div>
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Show on the shop</label>
    <button class="btn" type="submit">Save poshak</button>
  </form>
@endsection
