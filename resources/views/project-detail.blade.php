@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <header class="page-header">
        <div>
            <a href="{{ route('projects') }}" class="back-link">← Retour aux projets</a>
            <h1>{{ $project->name }}</h1>
        </div>
        <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary">✏️ Modifier</a>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="ticket-detail-container">

        {{-- ── Colonne principale ── --}}
        <div class="ticket-main">

            {{-- Infos générales --}}
            <div class="content-section">
                <h2>Informations générales</h2>

                <div class="ticket-meta">
                    <div class="meta-item">
                        <span class="meta-label">Client</span>
                        <span class="meta-value">{{ $project->client ?? '—' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Statut</span>
                        <span class="badge badge-{{ $project->status }}">
                            @switch($project->status)
                                @case('active')    Actif      @break
                                @case('on_hold')   En attente @break
                                @case('completed') Terminé    @break
                                @default {{ ucfirst($project->status) }}
                            @endswitch
                        </span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Date limite</span>
                        <span class="meta-value">{{ $project->deadline ?? '—' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Équipe</span>
                        <span class="meta-value">{{ $project->team ?? '—' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Créé le</span>
                        <span class="meta-value">{{ $project->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- Barre de progression --}}
                <div style="margin-top: 1.5rem">
                    <label style="font-weight:600">Progression — {{ $project->progress }}%</label>
                    <div class="progress-bar" style="margin-top:.5rem; height: 12px;">
                        <div class="progress-fill" style="width: {{ $project->progress }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Membres --}}
            <div class="content-section">
                <h2>Membres de l'équipe</h2>
                @if($project->users->isEmpty())
                    <p class="empty-state">Aucun membre associé à ce projet.</p>
                @else
                    <div class="users-checkbox-grid">
                        @foreach($project->users as $user)
                            <div class="user-checkbox-item" style="cursor:default">
                                <span class="user-checkbox-info">
                                    <span class="user-name">{{ $user->name }}</span>
                                    <span class="user-role">{{ ucfirst($user->role ?? 'user') }}</span>
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Tickets du projet --}}
            <div class="content-section">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem">
                    <h2 style="margin:0">Tickets ({{ $project->tickets->count() }})</h2>
                    <a href="{{ route('ticket-create') }}?project_id={{ $project->id }}" class="btn btn-primary btn-small">
                        + Nouveau ticket
                    </a>
                </div>

                @if($project->tickets->isEmpty())
                    <p class="empty-state">Aucun ticket pour ce projet.</p>
                @else
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th>Assigné à</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->tickets as $ticket)
                                <tr>
                                    <td>#T{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <a href="{{ route('ticket-detail', $ticket) }}">
                                            <strong>{{ $ticket->title }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $ticket->priority }}">
                                            @switch($ticket->priority)
                                                @case('low')      Basse   @break
                                                @case('medium')   Moyenne @break
                                                @case('high')     Haute   @break
                                                @case('critical') Urgente @break
                                                @default {{ ucfirst($ticket->priority ?? '—') }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $ticket->status }}">
                                            @switch($ticket->status)
                                                @case('open')        Ouvert    @break
                                                @case('in_progress') En cours  @break
                                                @case('done')        Résolu    @break
                                                @case('closed')      Fermé     @break
                                                @default {{ ucfirst($ticket->status) }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>{{ $ticket->assignedUser->name ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('ticket-detail', $ticket) }}" class="btn-icon" title="Voir">👁️</a>
                                        <a href="{{ route('tickets.edit', $ticket) }}" class="btn-icon" title="Modifier">✏️</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- ── Sidebar ── --}}
        <aside class="ticket-sidebar">

            {{-- Contrat --}}
            <div class="content-section">
                <h3>Contrat</h3>
                @if($project->contract)
                    @php $c = $project->contract; @endphp
                    <div class="detail-item">
                        <span class="detail-label">Heures incluses</span>
                        <span class="detail-value">{{ $c->hours_included ?? '—' }} h</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Heures utilisées</span>
                        <span class="detail-value">{{ $c->hours_used }} h</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Heures restantes</span>
                        <span class="detail-value"
                              style="color: {{ $c->remaining_hours < 10 ? '#e53e3e' : 'inherit' }}">
                            {{ $c->remaining_hours }} h
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Taux horaire</span>
                        <span class="detail-value">{{ $c->hourly_rate ? number_format($c->hourly_rate, 2) . ' €' : '—' }}</span>
                    </div>
                    @if($c->hours_included)
                        <div style="margin-top:.75rem">
                            <div class="progress-bar small">
                                <div class="progress-fill"
                                     style="width: {{ min(100, ($c->hours_used / $c->hours_included) * 100) }}%;
                                            background: {{ ($c->hours_used / $c->hours_included) > 0.8 ? '#e53e3e' : '' }}">
                                </div>
                            </div>
                            <span class="progress-text-small">
                                {{ round(($c->hours_used / $c->hours_included) * 100) }}% utilisé
                            </span>
                        </div>
                    @endif
                    <div class="detail-item" style="margin-top:.75rem">
                        <span class="detail-label">Du</span>
                        <span class="detail-value">{{ $c->start_date ?? '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Au</span>
                        <span class="detail-value">{{ $c->end_date ?? '—' }}</span>
                    </div>
                @else
                    <p class="empty-state">Aucun contrat associé.</p>
                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline btn-block" style="margin-top:.5rem">
                        + Ajouter un contrat
                    </a>
                @endif
            </div>

            {{-- Stats rapides --}}
            <div class="content-section">
                <h3>Statistiques</h3>
                <div class="detail-item">
                    <span class="detail-label">Total tickets</span>
                    <span class="detail-value">{{ $project->tickets->count() }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Ouverts</span>
                    <span class="detail-value">{{ $project->tickets->where('status','open')->count() }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">En cours</span>
                    <span class="detail-value">{{ $project->tickets->where('status','in_progress')->count() }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Résolus</span>
                    <span class="detail-value">{{ $project->tickets->where('status','done')->count() }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Temps total</span>
                    <span class="detail-value">{{ $project->tickets->sum('time_spent') }} min</span>
                </div>
            </div>

        </aside>
    </div>
@endsection
