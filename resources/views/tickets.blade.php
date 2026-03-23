@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
    <header class="page-header">
        <h1>Gestion des Tickets</h1>
        <a href="{{ route('ticket-create') }}" class="btn btn-primary">+ Nouveau ticket</a>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <div class="filters">
        <div class="filter-group">
            <label for="priority-filter">Priorité:</label>
            <select id="priority-filter">
                <option value="all">Toutes</option>
                <option value="low">Basse</option>
                <option value="medium">Moyenne</option>
                <option value="high">Haute</option>
                <option value="critical">Urgente</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="status-filter">Statut:</label>
            <select id="status-filter">
                <option value="all">Tous</option>
                <option value="open">Ouvert</option>
                <option value="in_progress">En cours</option>
                <option value="done">Résolu</option>
                <option value="closed">Fermé</option>
            </select>
        </div>
        <div class="search-box">
            <input type="search" id="search-input" placeholder="Rechercher un ticket...">
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="content-section">
        <table class="data-table" id="tickets-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Projet</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Assigné à</th>
                    <th>Date création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr data-priority="{{ $ticket->priority }}" data-status="{{ $ticket->status }}">
                        <td>#T{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <a href="{{ route('ticket-detail', $ticket) }}">
                                <strong>{{ $ticket->title }}</strong>
                            </a>
                        </td>
                        <td>{{ $ticket->project->name ?? '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $ticket->priority }}">
                                @switch($ticket->priority)
                                    @case('low') Basse @break
                                    @case('medium') Moyenne @break
                                    @case('high') Haute @break
                                    @case('critical') Urgente @break
                                    @default {{ ucfirst($ticket->priority ?? '—') }}
                                @endswitch
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $ticket->status }}">
                                @switch($ticket->status)
                                    @case('open') Ouvert @break
                                    @case('in_progress') En cours @break
                                    @case('done') Résolu @break
                                    @case('closed') Fermé @break
                                    @default {{ ucfirst($ticket->status) }}
                                @endswitch
                            </span>
                        </td>
                        <td>{{ $ticket->assignedUser->name ?? 'Non assigné' }}</td>
                        <td>{{ $ticket->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('ticket-detail', $ticket) }}" class="btn-icon" title="Voir">👁️</a>
                            <a href="{{ route('tickets.edit', $ticket) }}" class="btn-icon" title="Modifier">✏️</a>
                            <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" style="display:inline"
                                  onsubmit="return confirm('Supprimer ce ticket ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon" title="Supprimer">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state">Aucun ticket trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
    function filterTable() {
        const priority = document.getElementById('priority-filter').value;
        const status   = document.getElementById('status-filter').value;
        const search   = document.getElementById('search-input').value.toLowerCase();

        document.querySelectorAll('#tickets-table tbody tr').forEach(row => {
            const matchPriority = priority === 'all' || row.dataset.priority === priority;
            const matchStatus   = status === 'all'   || row.dataset.status === status;
            const matchSearch   = row.textContent.toLowerCase().includes(search);
            row.style.display   = (matchPriority && matchStatus && matchSearch) ? '' : 'none';
        });
    }

    document.getElementById('priority-filter').addEventListener('change', filterTable);
    document.getElementById('status-filter').addEventListener('change', filterTable);
    document.getElementById('search-input').addEventListener('input', filterTable);
</script>
@endpush
