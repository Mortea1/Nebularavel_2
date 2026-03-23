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

        <form class="login-form" id="loginForm">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="votre@email.com" >
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="••••••••" >
            </div>

            <div class="form-options">
                <a href="#" class="forgot-password">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>

        </form>
    </div>
</div>
<div id="toast" class="toast"></div>
<script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
