@extends('admin.layout')
@section('title', 'Pages')
@section('content')
  <div class="row">
    <h1>Pages</h1>
    <a class="btn" href="{{ route('admin.pages.create') }}">New page</a>
  </div>
  <table>
    <thead><tr><th>Page</th><th>Slug</th><th></th></tr></thead>
    <tbody>
      @foreach ($pages as $page)
        <tr>
          <td>{{ $page->title }}</td>
          <td>/{{ $page->slug }}</td>
          <td>
            <a href="{{ route('admin.pages.edit', $page) }}">Edit</a>
            <form method="post" action="{{ route('admin.pages.destroy', $page) }}" style="display:inline" onsubmit="return confirm('Remove this page?')">
              @csrf @method('DELETE')
              <button class="btn ghost" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
