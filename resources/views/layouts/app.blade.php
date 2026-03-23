<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon.png') }}">
    <title>@yield('title') - Nebula</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="app-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ url('/dashboard') }}">
                <img src="{{ asset('assets/images/nebula_logo.png') }}" alt="Logo Nebula" class="logo">
            </a>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ url('/dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span><span>Tableau de bord</span>
                @if(auth()->user()->isAdmin())
                    @php $pc = \App\Models\User::where('status','pending')->count(); @endphp
                    @if($pc > 0)
                        <span class="badge badge-critical" style="margin-left:auto; font-size:.7rem">{{ $pc }}</span>
                    @endif
                @endif
            </a>
            <a href="{{ url('/projects') }}" class="nav-item {{ request()->is('projects*') ? 'active' : '' }}">
                <span class="icon">📁</span><span>Projets</span>
            </a>
            <a href="{{ url('/tickets') }}" class="nav-item {{ request()->is('tickets*') || request()->is('ticket*') ? 'active' : '' }}">
                <span class="icon">🎫</span><span>Tickets</span>
            </a>
            <a href="{{ url('/profile') }}" class="nav-item {{ request()->is('profile') ? 'active' : '' }}">
                <span class="icon">👤</span><span>Profil</span>
            </a>

            @if(auth()->user()->isAdmin())
                <div class="nav-separator" style="border-top:1px solid rgba(255,255,255,.15); margin:.5rem 0;"></div>
                <a href="{{ route('admin.users') }}" class="nav-item {{ request()->is('admin*') ? 'active' : '' }}">
                    <span class="icon">🛡️</span><span>Utilisateurs</span>
                    @php $pc2 = \App\Models\User::where('status','pending')->count(); @endphp
                    @if($pc2 > 0)
                        <span class="badge badge-critical" style="margin-left:auto; font-size:.7rem">{{ $pc2 }}</span>
                    @endif
                </a>
            @endif

            <a href="{{ url('/help') }}" class="nav-item {{ request()->is('help') ? 'active' : '' }}">
                <span class="icon">❓</span><span>Aide</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item logout"
                        style="background:none; border:none; width:100%; text-align:left; cursor:pointer;">
                    <span class="icon">🚪</span><span>Déconnexion</span>
                </button>
            </form>
        </nav>
    </aside>

    <main class="main-content">
        {{-- Messages flash globaux --}}
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1rem">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert" style="background:#fff3cd; border:1px solid #f0c040; border-radius:8px; padding:.75rem 1rem; margin-bottom:1rem">
                {{ session('warning') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom:1rem">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

<div id="toast" class="toast"></div>
@stack('scripts')
</body>
</html>
