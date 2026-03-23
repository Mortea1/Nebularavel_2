@extends('layouts.app')

@section('title', 'Modifier – ' . $project->name)

@section('content')
    <header class="page-header">
        <div>
            <a href="{{ route('projects.show', $project) }}" class="back-link">← Retour au projet</a>
            <h1>Modifier : {{ $project->name }}</h1>
        </div>
    </header>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="form-container">
        <form method="POST" action="{{ route('projects.update', $project) }}">
            @csrf
            @method('PUT')

            {{-- ── Informations générales ── --}}
            <div class="content-section">
                <h2>Informations générales</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nom du projet *</label>
                        <input type="text" id="name" name="name"
                               value="{{ old('name', $project->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="client">Client</label>
                        <input type="text" id="client" name="client"
                               value="{{ old('client', $project->client) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Statut</label>
                        <select id="status" name="status">
                            <option value="active"    {{ old('status', $project->status) == 'active'    ? 'selected' : '' }}>Actif</option>
                            <option value="on_hold"   {{ old('status', $project->status) == 'on_hold'   ? 'selected' : '' }}>En attente</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Date limite</label>
                        <input type="date" id="deadline" name="deadline"
                               value="{{ old('deadline', $project->deadline) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="team">Équipe / note</label>
                    <input type="text" id="team" name="team"
                           value="{{ old('team', $project->team) }}">
                </div>

                {{-- Progression --}}
                <div class="form-group">
                    <label for="progress">
                        Progression : <span id="progress-value">{{ old('progress', $project->progress) }}%</span>
                    </label>
                    <input type="range" id="progress" name="progress"
                           min="0" max="100" step="5"
                           value="{{ old('progress', $project->progress) }}"
                           class="progress-slider">
                </div>
            </div>

            {{-- ── Membres de l'équipe ── --}}
            <div class="content-section">
                <h2>Membres de l'équipe</h2>
                <p class="form-hint">Cochez les membres à associer au projet.</p>
                <div class="form-group">
                    <div class="users-checkbox-grid">
                        @foreach($users as $user)
                            @php
                                $checked = in_array($user->id, old('user_ids', $project->users->pluck('id')->toArray()));
                            @endphp
                            <label class="user-checkbox-item">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                    {{ $checked ? 'checked' : '' }}>
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
                               value="{{ old('hours_included', $project->contract->hours_included ?? '') }}"
                               min="0">
                    </div>
                    <div class="form-group">
                        <label for="hourly_rate">Taux horaire (€)</label>
                        <input type="number" id="hourly_rate" name="hourly_rate"
                               value="{{ old('hourly_rate', $project->contract->hourly_rate ?? '') }}"
                               min="0" step="0.01">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Date de début</label>
                        <input type="date" id="start_date" name="start_date"
                               value="{{ old('start_date', $project->contract->start_date ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="end_date">Date de fin</label>
                        <input type="date" id="end_date" name="end_date"
                               value="{{ old('end_date', $project->contract->end_date ?? '') }}">
                    </div>
                </div>

                @if($project->contract)
                    <div class="form-row">
                        <div class="form-group">
                            <label>Heures utilisées</label>
                            <input type="number" name="hours_used"
                                   value="{{ old('hours_used', $project->contract->hours_used) }}"
                                   min="0">
                        </div>
                        <div class="form-group">
                            <label>Heures restantes</label>
                            <input type="text" disabled
                                   value="{{ $project->contract->remaining_hours }} h restantes">
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large">Enregistrer les modifications</button>
                <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary btn-large">Annuler</a>
            </div>
        </form>

        {{-- Danger zone --}}
        <div class="content-section" style="margin-top: 2rem; border: 1px solid #e53e3e; border-radius: 8px;">
            <h2 style="color:#e53e3e;">Zone de danger</h2>
            <div class="settings-item">
                <div class="settings-info">
                    <h3>Supprimer ce projet</h3>
                    <p>Cette action est irréversible. Tous les tickets associés seront supprimés.</p>
                </div>
                <form method="POST" action="{{ route('projects.destroy', $project) }}"
                      onsubmit="return confirm('Supprimer définitivement « {{ $project->name }} » ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const slider = document.getElementById('progress');
    const label  = document.getElementById('progress-value');
    slider.addEventListener('input', () => label.textContent = slider.value + '%');
</script>
@endpush
