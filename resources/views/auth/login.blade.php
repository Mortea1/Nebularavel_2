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
