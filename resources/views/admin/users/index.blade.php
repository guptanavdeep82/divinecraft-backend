@extends('admin.layout')
@section('title', 'Users')
@section('content')
  <h1>Users</h1>
  <table>
    <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Joined</th></tr></thead>
    <tbody>
      @forelse ($users as $user)
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->phone ?: '—' }}</td>
          <td>{{ $user->orders_count }}</td>
          <td>{{ $user->created_at->format('d M Y') }}</td>
        </tr>
      @empty
        <tr><td colspan="5">No one has registered yet.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
