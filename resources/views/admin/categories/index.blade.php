@extends('admin.layout')
@section('title', 'Categories')
@section('content')
  <h1>Categories</h1>
  <form class="panel" method="post" action="{{ route('admin.categories.store') }}" style="margin-bottom:18px">
    @csrf
    <div class="split">
      <label>Name <input name="name" required></label>
      <label>Sort <input type="number" name="sort" min="0" value="0"></label>
    </div>
    <button class="btn" type="submit">Add category</button>
  </form>
  <table>
    <thead><tr><th>Name</th><th>Poshaks</th><th></th></tr></thead>
    <tbody>
      @foreach ($categories as $category)
        <tr>
          <td>{{ $category->name }}</td>
          <td>{{ $category->products_count }}</td>
          <td>
            <form method="post" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Remove this category?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:none;border:0;color:#9e1b32;cursor:pointer">Remove</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
