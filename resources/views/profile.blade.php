@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')

    <header class="page-header">
        <h1>Mon Profil</h1>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="profile-container">

        {{-- Avatar + infos rapides --}}
        <div class="content-section profile-header-section">
            <div class="profile-avatar-section">
                <div class="profile-avatar">
                    <span class="avatar-placeholder">
                        <img
                            src="{{ asset('assets/img_profile/' . (Auth::user()->avatar ?? 'img1.png')) }}"
                            alt="Avatar"
                        >
                    </span>
                </div>
            </div>
            <div class="profile-header-info">
                <h2>{{ Auth::user()->full_name }}</h2>
                <p class="profile-role">{{ ucfirst(Auth::user()->role ?? 'Utilisateur') }}</p>
                <p class="profile-email">{{ Auth::user()->email }}</p>
                @if(Auth::user()->department)
                    <p class="profile-dept">{{ Auth::user()->department }}</p>
                @endif
            </div>
        </div>

        {{-- Informations personnelles --}}
        <div class="content-section">
            <h2>Informations personnelles</h2>
            <form id="profileForm" class="profile-form" method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" id="firstName" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}">
                    </div>
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" id="lastName" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nom d'affichage *</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                    </div>
                    <div class="form-group">
                        <label>Département</label>
                        <input type="text" name="department" value="{{ old('department', Auth::user()->department) }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </form>
        </div>

        {{-- Sécurité --}}
        <div class="content-section">
            <h2>Sécurité</h2>
            <p>Modifiez votre mot de passe pour sécuriser votre compte.</p><br>
            <button class="btn btn-outline" id="openPasswordModal">Changer le mot de passe</button>
        </div>

    </div>

    {{-- Modal changement de mot de passe --}}
    {{-- data-url évite d'écrire du Blade dans le JS externe --}}
    <dialog id="passwordModal" data-url="{{ route('profile.password') }}">
        <h2>Changer le mot de passe</h2>

        <div id="modalError" class="alert alert-error" style="display:none;"></div>

        <div class="form-group">
            <label>Mot de passe actuel *</label>
            <input type="password" id="current_password" class="form-control">
        </div>
        <div class="form-group">
            <label>Nouveau mot de passe *</label>
            <input type="password" id="new_password" class="form-control">
        </div>
        <div class="form-group">
            <label>Confirmer le nouveau mot de passe *</label>
            <input type="password" id="new_password_confirmation" class="form-control">
        </div>

        <div class="modal-actions">
            <button class="btn btn-secondary" id="closePasswordModal">Annuler</button>
            <button class="btn btn-primary" id="submitPassword">Modifier</button>
        </div>
    </dialog>

@endsection

@push('scripts')
    <script src="{{ asset('js/profile.js') }}"></script>
@endpush
