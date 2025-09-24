<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MedShare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root { --ms-primary: #198754; }
        body { background: #ffffff; }
        .ms-container { animation: fadeIn .4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px);} to { opacity: 1; transform: none;}}
        .ms-card { transition: transform .2s ease, box-shadow .2s ease; }
        .ms-card:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(0,0,0,.08); }
        .ms-appear { opacity: 0; transform: translateY(12px); animation: riseIn .5s ease forwards; }
        .ms-appear:nth-child(1){animation-delay:.05s}.ms-appear:nth-child(2){animation-delay:.1s}.ms-appear:nth-child(3){animation-delay:.15s}.ms-appear:nth-child(4){animation-delay:.2s}
        @keyframes riseIn { to { opacity:1; transform:none; }}
        .toast-container { z-index: 1080; }
        /* Make table header silver */
        .table thead th, .table thead td { background-color: #f1f3f5; color: #212529; }
        /* Form container shadow */
        .ms-form { box-shadow: 0 10px 30px rgba(0,0,0,.08); border: 1px solid #e9ecef; }
    </style>
</head>
<body>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ $errors->first() }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <div class="ms-container">@yield('content')</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            toastElList.forEach(function (toastEl) {
                try {
                    var t = new bootstrap.Toast(toastEl, { autohide: true, delay: 3000 });
                    t.show();
                } catch (e) {}
            });
        });
    </script>
</body>
</html>