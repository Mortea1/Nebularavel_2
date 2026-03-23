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
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('assets/img_profile/' . Auth::user()->avatar) }}" alt="Avatar">
                        @else
                            <img src="{{ asset('assets/img_profile/img1.png') }}" alt="Avatar">
                        @endif
                    </span>
                </div>
                <button class="btn btn-outline btn-small">Changer la photo</button>
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

        {{-- Infos personnelles --}}
        <div class="content-section">
            <h2>Informations personnelles</h2>
            <form class="profile-form" method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}">
                    </div>
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nom d'affichage *</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
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

        {{-- Bouton ouvrir modal --}}
        <div class="content-section">
            <h2>Sécurité</h2>
            <p>Modifiez votre mot de passe pour sécuriser votre compte.</p>
            <button class="btn btn-outline" id="openPasswordModal">🔒 Changer le mot de passe</button>
        </div>

    </div>

    {{-- Modal dialog --}}
    <dialog id="passwordModal" style="
        background: var(--bg-card, #1e1e2e);
        color: var(--text-primary, #fff);
        border: 1px solid var(--border-color, #333);
        border-radius: 12px;
        padding: 2rem;
        width: 100%;
        max-width: 460px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    ">
        <h2 style="margin-bottom: 1.5rem;">Changer le mot de passe</h2>

        <div id="modalError" class="alert alert-error" style="display:none; margin-bottom:1rem;"></div>

        <div class="form-group" style="margin-bottom:1rem;">
            <label>Mot de passe actuel *</label>
            <input type="password" id="current_password" class="form-control" style="width:100%;">
        </div>
        <div class="form-group" style="margin-bottom:1rem;">
            <label>Nouveau mot de passe *</label>
            <input type="password" id="new_password" class="form-control" style="width:100%;">
        </div>
        <div class="form-group" style="margin-bottom:1.5rem;">
            <label>Confirmer le nouveau mot de passe *</label>
            <input type="password" id="new_password_confirmation" class="form-control" style="width:100%;">
        </div>

        <div style="display:flex; gap:1rem; justify-content:flex-end;">
            <button class="btn btn-secondary" id="closePasswordModal">Annuler</button>
            <button class="btn btn-primary" id="submitPassword">Modifier</button>
        </div>
    </dialog>

@endsection

@push('scripts')
    <script>
        const modal        = document.getElementById('passwordModal');
        const openBtn      = document.getElementById('openPasswordModal');
        const closeBtn     = document.getElementById('closePasswordModal');
        const submitBtn    = document.getElementById('submitPassword');
        const modalError   = document.getElementById('modalError');

        // Ouvrir
        openBtn.addEventListener('click', () => {
            modalError.style.display = 'none';
            document.getElementById('current_password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('new_password_confirmation').value = '';
            modal.showModal();
        });

        // Fermer
        closeBtn.addEventListener('click', () => modal.close());
        modal.addEventListener('click', (e) => { if (e.target === modal) modal.close(); });

        // Soumettre via fetch
        submitBtn.addEventListener('click', async () => {
            modalError.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.textContent = 'En cours...';

            try {
                const response = await fetch('{{ route('profile.password') }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        current_password:      document.getElementById('current_password').value,
                        password:              document.getElementById('new_password').value,
                        password_confirmation: document.getElementById('new_password_confirmation').value,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    modal.close();
                    showToast('🔒 Mot de passe modifié avec succès !', 'success');
                } else {
                    // Affiche les erreurs de validation Laravel
                    const messages = data.errors
                        ? Object.values(data.errors).flat().join('<br>')
                        : (data.message || 'Une erreur est survenue.');
                    modalError.innerHTML = messages;
                    modalError.style.display = 'block';
                }

            } catch (err) {
                modalError.innerHTML = 'Erreur réseau, veuillez réessayer.';
                modalError.style.display = 'block';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Modifier';
            }
        });
    </script>
@endpush
