@php
  $current = old($name, $value ?? '');
  $url = \App\Support\Media::url($current);
@endphp
<div class="photo-field">
  <label>{{ $label }}
    <input name="{{ $name }}" value="{{ $current }}" placeholder="{{ $placeholder ?? '/images/your-photo.jpg' }}">
  </label>
  <figure class="preview" data-preview="{{ $name }}" @if ($url === '') hidden @endif>
    <img src="{{ $url }}" alt="">
  </figure>
  @if (!empty($fileName))
    <label class="file">{{ $fileLabel ?? 'Or upload a photo' }}
      <input type="file" name="{{ $fileName }}" accept="image/*" data-preview-input="{{ $name }}">
    </label>
  @endif
</div>
