@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
    <header class="page-header">
        <h1>Paramètres</h1>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="settings-container">
        <!-- General Settings -->
        <div class="content-section">
            <h2>Paramètres généraux</h2>

            <div class="settings-item">
                <div class="settings-info">
                    <h3>Langue</h3>
                    <p>Choisissez la langue de l'interface</p>
                </div>
                <select class="settings-control" id="lang-select">
                    <option value="fr" selected>Français</option>
                    <option value="en">English</option>
                    <option value="es">Español</option>
                    <option value="de">Deutsch</option>
                </select>
            </div>

            <div class="settings-item">
                <div class="settings-info">
                    <h3>Thème</h3>
                    <p>Choisissez le thème de l'interface</p>
                </div>
                <select class="settings-control" id="theme-select">
                    <option value="light" selected>Clair</option>
                    <option value="dark">Sombre</option>
                </select>
            </div>
        </div>

        <!-- Notifications -->
        <div class="content-section">
            <h2>Information</h2>

            <div class="settings-item">
                <div class="settings-info">
                    <h3>Notifications</h3>
                    <p>Recevoir des notifications</p>
                </div>
                <label class="toggle">
                    <input type="checkbox" id="notif-toggle" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Compte -->
        <div class="content-section">
            <h2>Compte</h2>

            <div class="settings-item">
                <div class="settings-info">
                    <h3>Connecté en tant que</h3>
                    <p>{{ Auth::user()->name }} — {{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('profile') }}" class="btn btn-outline">Modifier le profil</a>
            </div>
        </div>

        <!-- Reset -->
        <div class="content-section">
            <h2>Utiles</h2>
            <div class="settings-item">
                <div class="settings-info">
                    <h3>Réinitialiser les paramètres</h3>
                    <p>Restaurer tous les paramètres par défaut</p>
                </div>
                <button class="btn btn-danger" id="reset-btn">Réinitialiser</button>
            </div>
        </div>

        <!-- Save -->
        <div class="settings-actions" id="save-section">
            <button class="btn btn-primary btn-large" id="save-btn">Enregistrer tous les paramètres</button>
        </div>
    </div>

    <div id="toast" class="toast"></div>
@endsection

@push('scripts')
<script>
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = `toast show ${type}`;
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    document.getElementById('save-btn').addEventListener('click', function() {
        showToast('🎉 Paramètres enregistrés !', 'success');
    });

    document.getElementById('reset-btn').addEventListener('click', function() {
        document.getElementById('lang-select').value  = 'fr';
        document.getElementById('theme-select').value = 'light';
        document.getElementById('notif-toggle').checked = true;
        showToast('Paramètres réinitialisés.', 'success');
    });
</script>
@endpush
