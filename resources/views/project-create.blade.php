@extends('layouts.app')

@section('title', 'Nouveau projet')

@section('content')
    <header class="page-header">
        <div>
            <a href="{{ route('projects') }}" class="back-link">← Retour aux projets</a>
            <h1>Créer un nouveau projet</h1>
        </div>
    </header>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="form-container">
        <form method="POST" action="{{ route('projects.store') }}">
            @csrf

            {{-- ── Informations générales ── --}}
            <div class="content-section">
                <h2>Informations générales</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nom du projet *</label>
                        <input type="text" id="name" name="name"
                               value="{{ old('name') }}"
                               placeholder="Ex : Refonte site web" required>
                    </div>
                    <div class="form-group">
                        <label for="client">Client</label>
                        <input type="text" id="client" name="client"
                               value="{{ old('client') }}"
                               placeholder="Ex : Acme Corp">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Statut</label>
                        <select id="status" name="status">
                            <option value="active"    {{ old('status','active') == 'active'    ? 'selected' : '' }}>Actif</option>
                            <option value="on_hold"   {{ old('status') == 'on_hold'   ? 'selected' : '' }}>En attente</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Date limite</label>
                        <input type="date" id="deadline" name="deadline"
                               value="{{ old('deadline') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="team">Équipe / note</label>
                    <input type="text" id="team" name="team"
                           value="{{ old('team') }}"
                           placeholder="Ex : Équipe A">
                </div>

                {{-- Progression --}}
                <div class="form-group">
                    <label for="progress">Progression : <span id="progress-value">{{ old('progress', 0) }}%</span></label>
                    <input type="range" id="progress" name="progress"
                           min="0" max="100" step="5"
                           value="{{ old('progress', 0) }}"
                           class="progress-slider">
                </div>
            </div>

            {{-- ── Membres de l'équipe ── --}}
            <div class="content-section">
                <h2>Membres de l'équipe</h2>
                <div class="form-group">
                    <div class="users-checkbox-grid">
                        @foreach($users as $user)
                            <label class="user-checkbox-item">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                    {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}>
                                <span class="user-checkbox-info">
                                    <span class="user-name">{{ $user->name }}</span>
                                    <span class="user-role">{{ ucfirst($user->role ?? 'user') }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── Contrat ── --}}
            <div class="content-section">
                <h2>Contrat <span class="form-hint">(optionnel)</span></h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="hours_included">Heures incluses</label>
                        <input type="number" id="hours_included" name="hours_included"
                               value="{{ old('hours_included') }}"
                               min="0" placeholder="Ex : 200">
                    </div>
                    <div class="form-group">
                        <label for="hourly_rate">Taux horaire (€)</label>
                        <input type="number" id="hourly_rate" name="hourly_rate"
                               value="{{ old('hourly_rate') }}"
                               min="0" step="0.01" placeholder="Ex : 95.00">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Date de début</label>
                        <input type="date" id="start_date" name="start_date"
                               value="{{ old('start_date') }}">
                    </div>
                    <div class="form-group">
                        <label for="end_date">Date de fin</label>
                        <input type="date" id="end_date" name="end_date"
                               value="{{ old('end_date') }}">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large">Créer le projet</button>
                <a href="{{ route('projects') }}" class="btn btn-secondary btn-large">Annuler</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    const slider = document.getElementById('progress');
    const label  = document.getElementById('progress-value');
    slider.addEventListener('input', () => label.textContent = slider.value + '%');
</script>
@endpush
