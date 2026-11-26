@extends('admin.layout')
@section('title', 'FAQ')
@section('content')
  <div class="row">
    <h1>FAQ</h1>
    <a class="btn" href="{{ route('admin.faqs.create') }}">New question</a>
  </div>
  <table>
    <thead><tr><th>Question</th><th>Sort</th><th>Live</th><th></th></tr></thead>
    <tbody>
      @foreach ($faqs as $faq)
        <tr>
          <td>{{ $faq->question }}</td>
          <td>{{ $faq->sort }}</td>
          <td>{{ $faq->is_active ? 'Yes' : 'No' }}</td>
          <td>
            <a href="{{ route('admin.faqs.edit', $faq) }}">Edit</a>
            <form method="post" action="{{ route('admin.faqs.destroy', $faq) }}" style="display:inline" onsubmit="return confirm('Remove this question?')">
              @csrf @method('DELETE')
              <button class="btn ghost" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
