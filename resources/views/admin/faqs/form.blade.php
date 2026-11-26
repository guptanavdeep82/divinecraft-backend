@extends('admin.layout')
@section('title', $faq->exists ? 'Edit question' : 'New question')
@section('content')
  <div class="row">
    <h1>{{ $faq->exists ? 'Edit question' : 'New question' }}</h1>
    <a class="btn ghost" href="{{ route('admin.faqs') }}">All questions</a>
  </div>
  <form class="panel" method="post" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
    @csrf
    @if ($faq->exists) @method('PUT') @endif
    <label>Question <input name="question" value="{{ old('question', $faq->question) }}" required></label>
    <label>Answer <textarea name="answer" rows="5" required>{{ old('answer', $faq->answer) }}</textarea></label>
    <label>Sort <input name="sort" type="number" value="{{ old('sort', $faq->sort) }}"></label>
    <label class="checks"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $faq->is_active))> Show on the FAQ page</label>
    @if ($errors->any())
      <p class="error">{{ $errors->first() }}</p>
    @endif
    <button class="btn" type="submit">Save question</button>
  </form>
@endsection
