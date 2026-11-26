@extends('admin.layout')
@section('title', $blog->exists ? 'Edit story' : 'New story')
@section('content')
  <div class="row">
    <h1>{{ $blog->exists ? 'Edit story' : 'New story' }}</h1>
    <a class="btn ghost" href="{{ route('admin.blogs') }}">All stories</a>
  </div>
  <form class="panel" method="post" enctype="multipart/form-data" action="{{ $blog->exists ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}">
    @csrf
    @if ($blog->exists) @method('PUT') @endif
    <label>Title <input name="title" value="{{ old('title', $blog->title) }}" required></label>
    <label>Slug <input name="slug" value="{{ old('slug', $blog->slug) }}" placeholder="Left blank to build it from the title"></label>
    <label>Excerpt <textarea name="excerpt" rows="2" required>{{ old('excerpt', $blog->excerpt) }}</textarea></label>
    <label>Story <textarea name="body" rows="12" required>{{ old('body', $blog->body) }}</textarea></label>
    <label>Author <input name="author" value="{{ old('author', $blog->author) }}"></label>
    @include('admin.partials.photo', ['name' => 'image', 'value' => $blog->image, 'label' => 'Image path', 'fileName' => 'photo', 'fileLabel' => 'Replace image'])
    <label class="checks"><input type="checkbox" name="published" value="1" @checked(old('published', $blog->exists ? $blog->published_at !== null : true))> Show on the journal</label>
    @if ($errors->any())
      <p class="error">{{ $errors->first() }}</p>
    @endif
    <button class="btn" type="submit">Save story</button>
  </form>
@endsection
