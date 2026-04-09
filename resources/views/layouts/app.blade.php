<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mivan Billing & Invoice</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('invoices.index') }}">
            <img src="{{ asset('images/logo_white.png') }}" alt="Logo" style="width:30px;height:30px;border-radius:50%;object-fit:cover;">
            <span>Mivan Billing & Invoice</span>
        </a>
        @auth
            <div class="d-flex gap-2">
                <a href="{{ route('customers.index') }}" class="btn btn-outline-light btn-sm">Customers</a>
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-light btn-sm">Invoices</a>
                <a href="{{ route('settings.edit') }}" class="btn btn-outline-light btn-sm">Settings</a>
                <a href="{{ route('password.edit') }}" class="btn btn-outline-light btn-sm">Change password</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">Logout</button>
                </form>
            </div>
        @endauth
    </div>
</nav>

<main class="container pb-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
