@extends('admin.layout')
@section('title', 'Journal')
@section('content')
  <div class="row">
    <h1>Journal</h1>
    <a class="btn" href="{{ route('admin.blogs.create') }}">Add story</a>
  </div>
  <table>
    <thead><tr><th></th><th>Title</th><th>Status</th><th></th></tr></thead>
    <tbody>
      @forelse ($blogs as $blog)
        <tr>
          <td>@if ($blog->image)<img class="thumb" src="{{ \App\Support\Media::url($blog->image) }}" alt="">@endif</td>
          <td>{{ $blog->title }}<br><small>{{ $blog->author }}</small></td>
          <td>{{ $blog->published_at ? 'Live · '.$blog->published_at->format('d M Y') : 'Draft' }}</td>
          <td>
            <a href="{{ route('admin.blogs.edit', $blog) }}">Edit</a>
            <form method="post" action="{{ route('admin.blogs.destroy', $blog) }}" style="display:inline" onsubmit="return confirm('Remove this story?')">
              @csrf @method('DELETE')
              <button class="link" type="submit">Remove</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4">No stories yet.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
