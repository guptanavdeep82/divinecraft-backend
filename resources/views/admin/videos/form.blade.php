@extends('admin.layout')
@section('title', $video->exists ? 'Edit video' : 'New video')
@section('content')
  <div class="row">
    <h1>{{ $video->exists ? 'Edit video' : 'New video' }}</h1>
    <a class="btn ghost" href="{{ route('admin.videos') }}">All videos</a>
  </div>
  <form class="panel" method="post" enctype="multipart/form-data" action="{{ $video->exists ? route('admin.videos.update', $video) : route('admin.videos.store') }}">
    @csrf
    @if ($video->exists) @method('PUT') @endif
    <div class="split">
      <label>Label on the frame <input name="label" value="{{ old('label', $video->label) }}" required placeholder="POSHAK"></label>
      <label>Title <input name="title" value="{{ old('title', $video->title) }}" required></label>
    </div>
    <label>Poster path <input name="poster" value="{{ old('poster', $video->poster) }}" placeholder="/images/..."></label>
    <label>Poster image <input type="file" name="photo" accept="image/*"></label>
    <label>Video URL <input name="video_url" value="{{ old('video_url', $video->video) }}" placeholder="YouTube link or /videos/uploads/clip.mp4"></label>
    <label>Or upload an mp4 <input type="file" name="clip" accept="video/mp4,video/webm"></label>
    <div class="split">
      <label>Link, optional <input name="href" value="{{ old('href', $video->href) }}" placeholder="/product/mor-mukut-peela-poshak"></label>
      <label>Sort <input type="number" name="sort" value="{{ old('sort', $video->sort ?? 0) }}" min="0"></label>
    </div>
    <label class="checks"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $video->exists ? $video->is_active : true))> Show on the homepage</label>
    @if ($errors->any())
      <p class="error">{{ $errors->first() }}</p>
    @endif
    <button class="btn" type="submit">Save video</button>
  </form>
@endsection
