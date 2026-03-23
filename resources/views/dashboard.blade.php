@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <header class="page-header">
        <h1>Tableau de bord</h1>
        <div class="user-info">
            <span>Bienvenue, <strong>{{ Auth::user()->first_name ?? Auth::user()->name }}</strong></span>
        </div>
    </header>

    {{-- Alerte comptes en attente (admin seulement) --}}
    @if(auth()->user()->isAdmin() && $pending_count > 0)
        <div class="alert" style="background:#6ece4c; border:1px solid #8926a9; border-radius:8px; padding:1rem 1.25rem; margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center;">
            <span><strong>{{ $pending_count }} compte(s)</strong> en attente de validation.</span>
            <a href="{{ route('admin.users') }}" class="btn btn-primary btn-small">Gérer les utilisateurs</a>
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">📁</div>
            <div class="stat-info">
                <h3>{{ $stats['total_projects'] }}</h3>
                <p>Projets actifs</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-info">
                <h3>{{ $stats['open_tickets'] }}</h3>
                <p>Tickets ouverts</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">👥</div>
            <div class="stat-info">
                <h3>{{ $stats['total_users'] }}</h3>
                <p>Utilisateurs actifs</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">⏱️</div>
            <div class="stat-info">
                <h3>{{ $stats['hours_this_month'] }}min</h3>
                <p>Temps ce mois</p>
            </div>
        </div>
    </div>

    <section class="content-section">
        <h2>Tickets récents</h2>
        <div class="activity-list">
            @forelse($recent_tickets as $ticket)
                <div class="activity-item">
                    <div class="activity-icon {{ in_array($ticket->priority, ['critical','high']) ? 'red' : 'blue' }}">🎫</div>
                    <div class="activity-details">
                        <p>
                            <strong>
                                <a href="{{ route('ticket-detail', $ticket) }}">{{ $ticket->title }}</a>
                            </strong>
                        </p>
                        <p class="activity-meta">
                            {{ $ticket->project->name ?? 'Sans projet' }}
                            — {{ $ticket->assignedUser->name ?? 'Non assigné' }}
                            — <span class="badge badge-{{ $ticket->status }}">{{ ucfirst($ticket->status) }}</span>
                            — {{ $ticket->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="empty-state">Aucun ticket récent.</p>
            @endforelse
        </div>
    </section>

    <section class="content-section">
        <h2>Projets en cours</h2>
        <div class="projects-grid">
            @forelse($projects as $project)
                <div class="project-card">
                    <h3>
                        <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
                    </h3>
                    <p class="project-client">{{ $project->client }}</p>
                    <p class="project-status status-{{ $project->status }}">{{ ucfirst($project->status) }}</p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $project->progress }}%"></div>
                    </div>
                    <p class="progress-text">{{ $project->progress }}% complété</p>
                    <p class="activity-meta">{{ $project->tickets_count }} ticket(s)</p>
                </div>
            @empty
                <p class="empty-state">Aucun projet en cours.</p>
            @endforelse
        </div>
    </section>
@endsection
