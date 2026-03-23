<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nebula - Créer un compte</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">
<div class="login-container">
    <div class="login-box" style="max-width: 520px;">
        <div class="login-header">
            <h1>NEBULA</h1>
            <p>Créer un compte</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:1rem">
                <ul style="margin:0;padding-left:1.2rem">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form class="login-form" method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">Prénom *</label>
                    <input type="text" id="first_name" name="first_name"
                           value="{{ old('first_name') }}"
                           placeholder="Jean" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Nom *</label>
                    <input type="text" id="last_name" name="last_name"
                           value="{{ old('last_name') }}"
                           placeholder="Dupont" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="votre@email.com" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="text" id="phone" name="phone"
                           value="{{ old('phone') }}"
                           placeholder="+33 6 00 00 00 00">
                </div>
                <div class="form-group">
                    <label for="department">Département / Société</label>
                    <input type="text" id="department" name="department"
                           value="{{ old('department') }}"
                           placeholder="Ex : Développement">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Mot de passe *</label>
                    <input type="password" id="password" name="password"
                           placeholder="8 caractères minimum" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirmer *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           placeholder="••••••••" required>
                </div>
            </div>

            <p class="form-hint" style="margin-bottom:1rem; font-size:.85rem; color:#666;">
                ⏳ Votre compte sera examiné par un administrateur avant activation.
            </p>

            <button type="submit" class="btn btn-primary btn-block">Envoyer ma demande</button>

            <p style="text-align:center; margin-top:1rem; font-size:.9rem;">
                Déjà un compte ?
                <a href="{{ route('home') }}">Se connecter</a>
            </p>
        </form>
    </div>
</div>
</body>
</html>
