<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile Settings - MediShare</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />


  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    .content-wrapper {
      margin-left: 256px;
      width: calc(100% - 256px);
      min-height: 100vh;
      padding: 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .content-wrapper > .row {
      width: 100%;
      margin: 0;
      padding: 0;
    }
  </style>
</head>
<body>
  

  @include('sidebar')


  <div class="content-wrapper">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

  {{-- Lucide.js --}}
  <script src="https://unpkg.com/lucide@latest"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
      }

      const tabElements = document.querySelectorAll('[data-bs-toggle="tab"]');
      tabElements.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function () {
          if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
          }
        });
      });


      const currentUrl = window.location.href;
      document.querySelectorAll('.sidebar-link').forEach(link => {
        link.classList.remove('sidebar-active');
        if (link.href === currentUrl || currentUrl.includes(link.getAttribute('href'))) {
          link.classList.add('sidebar-active');
        }
      });
    });
  </script>

  @yield('scripts')
</body>
</html>
