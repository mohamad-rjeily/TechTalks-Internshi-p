<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="max-width: 500px;">
            <div class="card-body text-center">
                <h1 class="card-title h3 mb-4">
                    <span class="d-block mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-envelope-check-fill text-primary" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM8 9.77a1 1 0 0 0 .95-.69L16 2h-4.148v6.23a3.5 3.5 0 0 1-5.642 2.651.5.5 0 0 0-.69-.691c-1.637-.62-2.88-1.99-3.41-3.61v-.385a.5.5 0 0 0-.256-.448A2 2 0 0 1 .05 3.555z"/>
                            <path d="M16 10.354a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0zM12.972 8.64a.5.5 0 0 0-.708-.708L10 10.293 8.354 8.64a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0 0-.708z"/>
                        </svg>
                    </span>
                    Please verify your email
                </h1>
                <p class="text-muted">
                    We've sent a verification link to your email address. Please click the link to verify your account.
                </p>
                <hr>
                <p class="mt-4">Didn't receive the email?</p>
                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Send again</button><br>
                    </div>
                    @if (session('message'))
                        <div class="alert alert-danger">
                            {{ session('message') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>