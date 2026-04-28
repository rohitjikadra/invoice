<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mivan Billing & Invoice</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            .app-brand-logo { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
            .app-navbar { position: sticky; top: 0; z-index: 1030; }
            .app-top-actions { align-items: center; }
            .app-top-link {
                color: rgba(255,255,255,.82);
                text-decoration: none;
                font-size: .84rem;
                font-weight: 500;
                padding: .22rem .1rem;
                border-bottom: 2px solid transparent;
                transition: color .15s ease, border-color .15s ease;
            }
            .app-top-link:hover { color: #fff; }
            .app-top-link.active { color: #fff; border-bottom-color: #38bdf8; }
            .app-main { padding-top: 1rem; padding-bottom: 1.5rem; }
            .app-page-header { display: flex; justify-content: space-between; align-items: center; gap: .75rem; margin-bottom: 1rem; }
            .app-page-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
            .app-alert { border-radius: 10px; }
            .app-bottom-nav { display: none; }
            .mobile-card-wrap { display: none; }
            @media (max-width: 991px) {
                .app-top-link {
                    width: 100%;
                    padding: .5rem .25rem;
                    border-bottom: none;
                    border-radius: 8px;
                }
                .app-top-link.active { background: rgba(255,255,255,.1); }
                .app-page-header { flex-direction: column; align-items: flex-start; }
                .app-page-actions { width: 100%; }
                .app-page-actions .btn { flex: 1 1 auto; }
                .desktop-table-wrap { display: none !important; }
                .mobile-card-wrap { display: grid; gap: .5rem; }
                .app-mobile-card { border: 1px solid #e5e7eb; border-radius: 12px; background: #fff; padding: .8rem; }
                .app-mobile-card .title { font-weight: 600; margin-bottom: .35rem; }
                .app-mobile-card .meta { font-size: .82rem; color: #4b5563; }
                .app-mobile-actions { display: flex; gap: .4rem; flex-wrap: wrap; }
                .app-mobile-actions .btn { flex: 1 1 auto; min-width: 84px; }
                .app-bottom-nav {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    position: fixed;
                    left: 0; right: 0; bottom: 0;
                    height: 56px;
                    background: #111827;
                    border-top: 1px solid rgba(255,255,255,.15);
                    z-index: 1040;
                }
                .app-bottom-link { display: grid; place-items: center; color: rgba(255,255,255,.8); text-decoration: none; font-size: .78rem; font-weight: 600; }
                .app-bottom-link.active { color: #fff; background: rgba(255,255,255,.08); }
                .app-main { padding-bottom: 4.5rem; }
                .app-submit-bar { position: sticky; bottom: 62px; background: rgba(248,250,252,.95); padding-top: .5rem; z-index: 5; }
            }
        </style>
    @endif
</head>
<body class="bg-light app-body">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark app-navbar">
    <div class="container app-container">
        <a class="navbar-brand d-flex align-items-center gap-2 app-brand" href="{{ route('invoices.index') }}">
            <img src="{{ asset('images/logo_white.png') }}" alt="Logo" class="app-brand-logo">
            <span>Mivan Billing & Invoice</span>
        </a>
        @auth
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNav" aria-controls="appNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mt-2 mt-lg-0" id="appNav">
                <div class="d-flex flex-column flex-lg-row ms-lg-auto gap-2 app-top-actions">
                    <a href="{{ route('customers.index') }}" class="app-top-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">Customers</a>
                    <a href="{{ route('invoices.index') }}" class="app-top-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">Invoices</a>
                    <a href="{{ route('delivery-challans.index') }}" class="app-top-link {{ request()->routeIs('delivery-challans.*') ? 'active' : '' }}">Delivery Challans</a>
                    <a href="{{ route('settings.edit') }}" class="app-top-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">Settings</a>
                    <a href="{{ route('password.edit') }}" class="app-top-link {{ request()->routeIs('password.*') ? 'active' : '' }}">Change Password</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">Logout</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>

<main class="container app-container app-main">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show app-alert" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger app-alert">
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
@auth
    <nav class="app-bottom-nav d-lg-none">
        <a href="{{ route('invoices.index') }}" class="app-bottom-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">Invoices</a>
        <a href="{{ route('delivery-challans.index') }}" class="app-bottom-link {{ request()->routeIs('delivery-challans.*') ? 'active' : '' }}">Challans</a>
        <a href="{{ route('customers.index') }}" class="app-bottom-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">Customers</a>
        <a href="{{ route('settings.edit') }}" class="app-bottom-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">Settings</a>
    </nav>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
