@extends('layouts.app')

@section('title', 'Projets')

@section('content')
    <header class="page-header">
        <h1>Mes Projets</h1>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">+ Nouveau projet</a>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <div class="filters">
        <div class="filter-group">
            <label for="status-filter">Statut:</label>
            <select id="status-filter">
                <option value="all">Tous</option>
                <option value="active">Actif</option>
                <option value="on_hold">En attente</option>
                <option value="completed">Terminé</option>
            </select>
        </div>
        <div class="search-box">
            <input type="search" id="search-input" placeholder="Rechercher un projet...">
        </div>
    </div>

    <!-- Projects Table -->
    <div class="content-section">
        <table class="data-table" id="projects-table">
            <thead>
                <tr>
                    <th>Nom du projet</th>
                    <th>Client</th>
                    <th>Statut</th>
                    <th>Progression</th>
                    <th>Date limite</th>
                    <th>Tickets</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr data-status="{{ $project->status }}">
                        <td><strong>{{ $project->name }}</strong></td>
                        <td>{{ $project->client ?? '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $project->status }}">
                                @switch($project->status)
                                    @case('active') En cours @break
                                    @case('on_hold') En attente @break
                                    @case('completed') Terminé @break
                                    @default {{ ucfirst($project->status) }}
                                @endswitch
                            </span>
                        </td>
                        <td>
                            <div class="progress-bar small">
                                <div class="progress-fill" style="width: {{ $project->progress }}%"></div>
                            </div>
                            <span class="progress-text-small">{{ $project->progress }}%</span>
                        </td>
                        <td>{{ $project->deadline ?? '—' }}</td>
                        <td>{{ $project->tickets_count }}</td>
                        <td>
                            <a href="{{ route('projects.show', $project) }}" class="btn-icon" title="Voir">👁️</a>
                            <a href="{{ route('projects.edit', $project) }}" class="btn-icon" title="Modifier">✏️</a>
                            <form method="POST" action="{{ route('projects.destroy', $project) }}" style="display:inline"
                                  onsubmit="return confirm('Supprimer ce projet ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon" title="Supprimer">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">Aucun projet trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
    // Filtre statut
    document.getElementById('status-filter').addEventListener('change', function() {
        const val = this.value;
        document.querySelectorAll('#projects-table tbody tr').forEach(row => {
            row.style.display = (val === 'all' || row.dataset.status === val) ? '' : 'none';
        });
    });

    // Recherche
    document.getElementById('search-input').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#projects-table tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
</script>
@endpush
