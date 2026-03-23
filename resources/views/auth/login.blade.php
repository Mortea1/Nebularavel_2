<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nebula - Connexion</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="login-page">
<div class="login-container">
    <div class="login-box">

        <div class="login-header">
            <h1>NEBULA</h1>
            <p>Votre projet au coeur de notre univers</p>
        </div>

        {{-- Messages flash (pending/rejected renvoyés par le middleware) --}}
        @if(session('warning'))
            <div class="error-box" style="background:#fff3cd; border-color:#f0c040; color:#856404;">
                ⚠️ {{ session('warning') }}
            </div>
        @endif
        @if(session('error'))
            <div class="error-box" style="background:#fde8e8; border-color:#e53e3e; color:#c53030;">
                ❌ {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="error-box" style="background:#e8f5e9; border-color:#48bb78; color:#276749;">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Erreurs de connexion --}}
        @if($errors->any())
            <div class="error-box">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       placeholder="votre@email.com"
                       value="{{ old('email') }}"
                       required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>

            <div class="form-options">
                <label>
                    <input type="checkbox" name="remember"> Se souvenir de moi
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-password">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Se connecter
            </button>

        </form>

        {{-- Lien vers le register --}}
        <p style="text-align:center; margin-top:1.25rem; font-size:.9rem; color:#666;">
            Pas encore de compte ?
            <a href="{{ route('register') }}" style="font-weight:600;">
                Faire une demande d'accès
            </a>
        </p>

    </div>
</div>

<div id="toast" class="toast"></div>
<script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
