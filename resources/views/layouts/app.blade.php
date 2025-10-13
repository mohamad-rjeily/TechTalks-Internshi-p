<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>MedShare</title>

  {{-- Bootstrap + Icons (CSS only) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  @livewireStyles

  <style>



    :root { --ms-primary:#198754; }
    body { background:#fff; }
    .toast-container { z-index:1080; }
    .table thead th, .table thead td { background:#f1f3f5; }
    .modal-backdrop{ z-index:1080!important; }
    .modal{ z-index:1085!important; }
    .modal .modal-content{ pointer-events:auto; }
  </style>
</head>
<body>
  {{-- Global toasts --}}
  <div class="toast-container position-fixed top-0 end-0 p-3">
    @if(session('success'))
      <div class="toast align-items-center text-bg-success border-0 show" role="alert">
        <div class="d-flex">
          <div class="toast-body">{{ session('success') }}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    @endif
    @if($errors->any())
      <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
        <div class="d-flex">
          <div class="toast-body">{{ $errors->first() }}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    @endif
  </div>

  <div class="container my-4">
    {{-- If this view is used as a Livewire PAGE layout, $slot is set. --}}
    @isset($slot)
      {{ $slot }}
    @else
      {{-- Otherwise, render Blade sections for classic views that extend the layout. --}}
      @hasSection('content')
        @yield('content')
      @endif
    @endisset
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @livewireScripts

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.toast').forEach(el => {
        try { new bootstrap.Toast(el, { autohide: true, delay: 3000 }).show(); } catch (e) {}
      });
    });
  </script>

  @stack('scripts')
</body>
</html>
