@if ($alert = App::alertBar())
  <div class="alert-bar" role="alert" aria-live="polite">
    <div class="container-wide text-center">
      {!! $alert !!}
    </div>
  </div>
@endif
