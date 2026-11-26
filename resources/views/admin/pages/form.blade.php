@extends('admin.layout')
@section('title', $page->exists ? 'Edit page' : 'New page')
@section('content')
  <div class="row">
    <h1>{{ $page->exists ? 'Edit page' : 'New page' }}</h1>
    <a class="btn ghost" href="{{ route('admin.pages') }}">All pages</a>
  </div>
  <form class="panel" method="post" action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}">
    @csrf
    @if ($page->exists) @method('PUT') @endif
    <label>Title <input name="title" value="{{ old('title', $page->title) }}" required></label>
    <label>Slug <input name="slug" value="{{ old('slug', $page->slug) }}" placeholder="about, policies, size-guide, contact, custom, faq"></label>
    <label>Eyebrow <input name="eyebrow" value="{{ old('eyebrow', $page->eyebrow) }}"></label>
    <label>Summary <textarea name="summary" rows="2">{{ old('summary', $page->summary) }}</textarea></label>
    <label>Body <textarea name="body" rows="14">{{ old('body', $page->body) }}</textarea></label>
    <p class="error" style="color:#5e4e44">Separate paragraphs with a blank line. Start a paragraph with ## to make a heading.</p>
    @if ($errors->any())
      <p class="error">{{ $errors->first() }}</p>
    @endif
    <button class="btn" type="submit">Save page</button>
  </form>
@endsection
