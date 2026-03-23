@extends('layouts.app')

@section('title', 'Modifier le ticket')

@section('content')
    <header class="page-header">
        <div>
            <a href="{{ route('ticket-detail', $ticket) }}" class="back-link">← Retour au ticket</a>
            <h1>Modifier : {{ $ticket->title }}</h1>
        </div>
        <span class="badge badge-{{ $ticket->status }}">
            @switch($ticket->status)
                @case('open')        Ouvert   @break
                @case('in_progress') En cours @break
                @case('done')        Résolu   @break
                @case('closed')      Fermé    @break
                @default {{ ucfirst($ticket->status) }}
            @endswitch
        </span>
    </header>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="form-container">
        <form method="POST" action="{{ route('tickets.update', $ticket) }}">
            @csrf
            @method('PUT')

            {{-- ── Informations générales ── --}}
            <div class="content-section">
                <h2>Informations générales</h2>

                <div class="form-group">
                    <label for="title">Titre du ticket *</label>
                    <input type="text" id="title" name="title"
                           value="{{ old('title', $ticket->title) }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="project_id">Projet</label>
                        <select id="project_id" name="project_id">
                            <option value="">Aucun projet</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id', $ticket->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select id="type" name="type">
                            <option value="">—</option>
                            <option value="bug"         {{ old('type', $ticket->type) == 'bug'         ? 'selected' : '' }}>Bug</option>
                            <option value="feature"     {{ old('type', $ticket->type) == 'feature'     ? 'selected' : '' }}>Nouvelle fonctionnalité</option>
                            <option value="improvement" {{ old('type', $ticket->type) == 'improvement' ? 'selected' : '' }}>Amélioration</option>
                            <option value="task"        {{ old('type', $ticket->type) == 'task'        ? 'selected' : '' }}>Tâche</option>
                            <option value="maintenance" {{ old('type', $ticket->type) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="priority">Priorité *</label>
                        <select id="priority" name="priority">
                            <option value="low"      {{ old('priority', $ticket->priority) == 'low'      ? 'selected' : '' }}>Basse</option>
                            <option value="medium"   {{ old('priority', $ticket->priority) == 'medium'   ? 'selected' : '' }}>Moyenne</option>
                            <option value="high"     {{ old('priority', $ticket->priority) == 'high'     ? 'selected' : '' }}>Haute</option>
                            <option value="critical" {{ old('priority', $ticket->priority) == 'critical' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Statut *</label>
                        <select id="status" name="status">
                            <option value="open"        {{ old('status', $ticket->status) == 'open'        ? 'selected' : '' }}>Ouvert</option>
                            <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="done"        {{ old('status', $ticket->status) == 'done'        ? 'selected' : '' }}>Résolu</option>
                            <option value="closed"      {{ old('status', $ticket->status) == 'closed'      ? 'selected' : '' }}>Fermé</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="assigned_to">Assigné à</label>
                        <select id="assigned_to" name="assigned_to">
                            <option value="">Non assigné</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('assigned_to', $ticket->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">Catégorie</label>
                        <select id="category" name="category">
                            <option value="">—</option>
                            <option value="frontend"      {{ old('category', $ticket->category) == 'frontend'      ? 'selected' : '' }}>Interface utilisateur</option>
                            <option value="backend"       {{ old('category', $ticket->category) == 'backend'       ? 'selected' : '' }}>Backend</option>
                            <option value="database"      {{ old('category', $ticket->category) == 'database'      ? 'selected' : '' }}>Base de données</option>
                            <option value="performance"   {{ old('category', $ticket->category) == 'performance'   ? 'selected' : '' }}>Performance</option>
                            <option value="security"      {{ old('category', $ticket->category) == 'security'      ? 'selected' : '' }}>Sécurité</option>
                            <option value="devops"        {{ old('category', $ticket->category) == 'devops'        ? 'selected' : '' }}>DevOps</option>
                            <option value="documentation" {{ old('category', $ticket->category) == 'documentation' ? 'selected' : '' }}>Documentation</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="in_contract" value="1"
                            {{ old('in_contract', $ticket->in_contract) ? 'checked' : '' }}>
                        Ce ticket est inclus dans le contrat
                    </label>
                </div>
            </div>

            {{-- ── Description ── --}}
            <div class="content-section">
                <h2>Description</h2>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="6">{{ old('description', $ticket->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="steps">Étapes pour reproduire</label>
                    <textarea id="steps" name="steps" rows="4">{{ old('steps', $ticket->steps) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="expected">Comportement attendu</label>
                    <textarea id="expected" name="expected" rows="3">{{ old('expected', $ticket->expected) }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large">Enregistrer les modifications</button>
                <a href="{{ route('ticket-detail', $ticket) }}" class="btn btn-secondary btn-large">Annuler</a>
            </div>
        </form>

        {{-- Danger zone --}}
        <div class="content-section" style="margin-top:2rem; border:1px solid #e53e3e; border-radius:8px;">
            <h2 style="color:#e53e3e;">Zone de danger</h2>
            <div class="settings-item">
                <div class="settings-info">
                    <h3>Supprimer ce ticket</h3>
                    <p>Cette action est irréversible.</p>
                </div>
                <form method="POST" action="{{ route('tickets.destroy', $ticket) }}"
                      onsubmit="return confirm('Supprimer définitivement ce ticket ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
@endsection
