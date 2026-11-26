@extends('admin.layout')
@section('title', 'Settings')
@section('content')
  <h1>Settings</h1>
  <form class="panel" method="post" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')
    <div class="split">
      <label>WhatsApp number <input name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" required placeholder="918285003300"></label>
      <label>Number as shown <input name="whatsapp_label" value="{{ old('whatsapp_label', $settings['whatsapp_label'] ?? '') }}" required></label>
    </div>
    <label>Announcement 1 <input name="announce_1" value="{{ old('announce_1', $settings['announce_1'] ?? '') }}"></label>
    <label>Announcement 2 <input name="announce_2" value="{{ old('announce_2', $settings['announce_2'] ?? '') }}"></label>
    <label>Announcement 3 <input name="announce_3" value="{{ old('announce_3', $settings['announce_3'] ?? '') }}"></label>
    <label>Announcement 4 <input name="announce_4" value="{{ old('announce_4', $settings['announce_4'] ?? '') }}"></label>
    <h2 style="margin:12px 0 0;font-size:28px">Razorpay</h2>
    <p style="margin:0;font-family:system-ui,sans-serif;color:#5e4e44">{{ $razorpayReady ? 'Keys are saved. Checkout can take payments.' : 'Add the test or live key from the Razorpay dashboard. Checkout stays closed until both are saved.' }}</p>
    <div class="split">
      <label>Key ID <input name="razorpay_key_id" value="{{ old('razorpay_key_id', $settings['razorpay_key_id'] ?? '') }}" placeholder="rzp_test_..."></label>
      <label>Key secret <input type="password" name="razorpay_key_secret" placeholder="{{ $secretSaved ? 'Saved. Type a new secret to replace it.' : 'Paste the secret' }}" autocomplete="new-password"></label>
    </div>
    <h2 style="margin:12px 0 0;font-size:28px">Shiprocket</h2>
    <p style="margin:0;font-family:system-ui,sans-serif;color:#5e4e44">{{ $shiprocketReady ? 'Shiprocket is connected. Paid orders can be booked and tracked.' : 'Add the API user from Shiprocket → Settings → API. Tracking stays on the studio status until both are saved.' }}</p>
    <div class="split">
      <label>API email <input type="email" name="shiprocket_email" value="{{ old('shiprocket_email', $settings['shiprocket_email'] ?? '') }}" placeholder="api-user@studio.com"></label>
      <label>API password <input type="password" name="shiprocket_password" placeholder="{{ $shiprocketPasswordSaved ? 'Saved. Type a new password to replace it.' : 'API user password' }}" autocomplete="new-password"></label>
    </div>
    <div class="split">
      <label>Pickup location name <input name="shiprocket_pickup" value="{{ old('shiprocket_pickup', $settings['shiprocket_pickup'] ?? 'Primary') }}" placeholder="Primary"></label>
      <label>Pickup pincode <input name="shiprocket_pickup_pincode" value="{{ old('shiprocket_pickup_pincode', $settings['shiprocket_pickup_pincode'] ?? '') }}" placeholder="281121" inputmode="numeric"></label>
    </div>
    <button class="btn" type="submit">Save settings</button>
  </form>
@endsection
