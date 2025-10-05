<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MediShare Dashboard</title>

  {{-- TailwindCSS + Lucide --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  @stack('styles')
</head>

<body class="bg-gradient-to-tr from-green-50 via-green-50 to-white min-h-screen fixed">

  <div class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('sidebar')

    {{-- Page Content --}}
    <main class="flex-1 md:ml-64">
        @yield('content')
    </main>
  </div>

  @stack('scripts')
</body>
</html>
