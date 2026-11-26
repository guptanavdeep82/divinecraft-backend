@extends('admin.layout')
@section('title', 'Enquiries')
@section('content')
  <h1>Enquiries</h1>
  <table>
    <thead><tr><th>When</th><th>From</th><th>Poshak</th><th>Note</th><th>Status</th></tr></thead>
    <tbody>
      @forelse ($enquiries as $enquiry)
        <tr>
          <td>{{ $enquiry->created_at->format('d M, H:i') }}</td>
          <td>{{ $enquiry->name }}<br>{{ $enquiry->phone }}</td>
          <td>{{ $enquiry->product?->name ?? '—' }}</td>
          <td style="white-space:pre-wrap">{{ $enquiry->message }}</td>
          <td>
            <form method="post" action="{{ route('admin.enquiries.update', $enquiry) }}">
              @csrf @method('PUT')
              <select name="status" onchange="this.form.submit()">
                @foreach (['new', 'read', 'closed'] as $status)
                  <option value="{{ $status }}" @selected($enquiry->status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
              </select>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5">No enquiries yet. They arrive when someone writes from a poshak page.</td></tr>
      @endforelse
    </tbody>
  </table>
@endsection
