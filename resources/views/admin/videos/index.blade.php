@extends('admin.layout')
@section('title', 'Videos')
@section('content')
  <div class="row">
    <h1>Videos</h1>
    <a class="btn" href="{{ route('admin.videos.create') }}">Add video</a>
  </div>
  <table>
    <thead><tr><th></th><th>Label</th><th>Title</th><th>Clip</th><th></th></tr></thead>
    <tbody>
      @forelse ($videos as $video)
        <tr>
          <td>@if ($video->poster)<img class="thumb" src="{{ \App\Support\Media::url($video->poster) }}" alt="">@endif</td>
          <td>{{ $video->label }}<br><small>{{ $video->is_active ? 'Live' : 'Hidden' }}</small></td>
          <td>{{ $video->title }}</td>
          <td>{{ $video->video ? 'Attached' : 'Poster only' }}</td>
          <td>
            <a href="{{ route('admin.videos.edit', $video) }}">Edit</a>
            <form method="post" action="{{ route('admin.videos.destroy', $video) }}" style="display:inline" onsubmit="return confirm('Remove this video?')">
              @csrf @method('DELETE')
              <button class="link" type="submit">Remove</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5">No videos yet.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
