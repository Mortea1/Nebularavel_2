@extends('layouts.app')

@section('title', 'Ticket #T{{ str_pad($ticket->id, 3, "0", STR_PAD_LEFT) }}')

@section('content')
    <header class="page-header">
        <div>
            <a href="{{ route('tickets') }}" class="back-link">← Retour aux tickets</a>
            <h1>Ticket #T{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</h1>
        </div>
        <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-primary">Modifier</a>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="ticket-detail-container">
        <!-- Ticket Main Info -->
        <div class="ticket-main">
            <div class="content-section">
                <div class="ticket-header-info">
                    <h2>{{ $ticket->title }}</h2>
                    <div class="ticket-badges">
                        <span class="badge badge-{{ $ticket->priority }}">
                            @switch($ticket->priority)
                                @case('low') Basse @break
                                @case('medium') Moyenne @break
                                @case('high') Haute @break
                                @case('critical') Urgente @break
                                @default {{ ucfirst($ticket->priority ?? '—') }}
                            @endswitch
                        </span>
                        <span class="badge badge-{{ $ticket->status }}">
                            @switch($ticket->status)
                                @case('open') Ouvert @break
                                @case('in_progress') En cours @break
                                @case('done') Résolu @break
                                @case('closed') Fermé @break
                                @default {{ ucfirst($ticket->status) }}
                            @endswitch
                        </span>
                    </div>
                </div>

                <div class="ticket-meta">
                    <div class="meta-item">
                        <span class="meta-label">Créé par:</span>
                        <span class="meta-value">{{ $ticket->creator ?? '—' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Créé le:</span>
                        <span class="meta-value">{{ $ticket->created_at->format('d M Y à H:i') }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Dernière mise à jour:</span>
                        <span class="meta-value">{{ $ticket->updated_at->format('d M Y à H:i') }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Temps passé:</span>
                        <span class="meta-value">{{ $ticket->time_spent }} min</span>
                    </div>
                </div>

                <div class="ticket-description">
                    <h3>Description</h3>
                    <p>{{ $ticket->description ?? 'Aucune description.' }}</p>

                    @if($ticket->steps)
                        <h4>Étapes pour reproduire :</h4>
                        <div style="white-space: pre-line">{{ $ticket->steps }}</div>
                    @endif

                    @if($ticket->expected)
                        <h4>Comportement attendu :</h4>
                        <p>{{ $ticket->expected }}</p>
                    @endif
                </div>
            </div>

            <!-- Temps passé (TPS) -->
            <div class="content-section">
                <h3>Temps passé</h3>

                @forelse($ticket->tps as $tp)
                    <div class="comment">
                        <div class="comment-header">
                            <strong>{{ $tp->user->name ?? '—' }}</strong>
                            <span class="comment-date">{{ $tp->created_at->format('d M Y à H:i') }}</span>
                            <span class="badge">{{ $tp->time }} min</span>
                        </div>
                        @if($tp->comment)
                            <div class="comment-body"><p>{{ $tp->comment }}</p></div>
                        @endif
                    </div>
                @empty
                    <p class="empty-state">Aucun temps enregistré.</p>
                @endforelse

                <div class="add-comment" style="margin-top:1rem">
                    <h4>Ajouter du temps</h4>
                    <form method="POST" action="{{ route('tickets.time', $ticket) }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label>Temps (minutes) *</label>
                                <input type="number" name="time" min="1" placeholder="60">
                            </div>
                            <div class="form-group">
                                <label>Commentaire</label>
                                <input type="text" name="comment" placeholder="Ce que vous avez fait...">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>

            <!-- Validations -->
            <div class="content-section">
                <h3>Validations</h3>

                @forelse($ticket->validations as $validation)
                    <div class="comment">
                        <div class="comment-header">
                            <strong>{{ $validation->user->name ?? '—' }}</strong>
                            <span class="comment-date">{{ $validation->created_at->format('d M Y') }}</span>
                            <span class="badge badge-{{ $validation->decision }}">{{ ucfirst($validation->decision) }}</span>
                        </div>
                        @if($validation->comment)
                            <div class="comment-body"><p>{{ $validation->comment }}</p></div>
                        @endif
                    </div>
                @empty
                    <p class="empty-state">Aucune validation.</p>
                @endforelse

                <div class="add-comment" style="margin-top:1rem">
                    <h4>Ajouter une validation</h4>
                    <form method="POST" action="{{ route('tickets.validation', $ticket) }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label>Décision *</label>
                                <select name="decision">
                                    <option value="pending">En attente</option>
                                    <option value="approved">Approuvé</option>
                                    <option value="rejected">Rejeté</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Commentaire</label>
                                <input type="text" name="comment" placeholder="Votre commentaire...">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ticket Sidebar -->
        <aside class="ticket-sidebar">
            <div class="content-section">
                <h3>Détails</h3>

                <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                    @csrf
                    @method('PUT')

                    <div class="detail-item">
                        <span class="detail-label">Projet</span>
                        <span class="detail-value">{{ $ticket->project->name ?? '—' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Assigné à</span>
                        <select class="form-select" name="assigned_to">
                            <option value="">Non assigné</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $ticket->assigned_to == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Priorité</span>
                        <select class="form-select" name="priority">
                            <option value="low"      {{ $ticket->priority == 'low' ? 'selected' : '' }}>Basse</option>
                            <option value="medium"   {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Moyenne</option>
                            <option value="high"     {{ $ticket->priority == 'high' ? 'selected' : '' }}>Haute</option>
                            <option value="critical" {{ $ticket->priority == 'critical' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Statut</span>
                        <select class="form-select" name="status">
                            <option value="open"        {{ $ticket->status == 'open' ? 'selected' : '' }}>Ouvert</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="done"        {{ $ticket->status == 'done' ? 'selected' : '' }}>Résolu</option>
                            <option value="closed"      {{ $ticket->status == 'closed' ? 'selected' : '' }}>Fermé</option>
                        </select>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Type</span>
                        <span class="detail-value">{{ ucfirst($ticket->type ?? '—') }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Catégorie</span>
                        <span class="detail-value">{{ ucfirst($ticket->category ?? '—') }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Dans le contrat</span>
                        <span class="detail-value">{{ $ticket->in_contract ? '✅ Oui' : '❌ Non' }}</span>
                    </div>

                    {{-- Champs cachés requis par le validator --}}
                    <input type="hidden" name="title" value="{{ $ticket->title }}">
                    <input type="hidden" name="project_id" value="{{ $ticket->project_id }}">

                    <button type="submit" class="btn btn-block btn-secondary">Enregistrer les modifications</button>
                </form>
            </div>

            <div class="content-section">
                <h3>Danger</h3>
                <form method="POST" action="{{ route('tickets.destroy', $ticket) }}"
                      onsubmit="return confirm('Supprimer ce ticket définitivement ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-block btn-danger">🗑️ Supprimer le ticket</button>
                </form>
            </div>
        </aside>
    </div>
@endsection
