@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
    <header class="page-header">
        <h1>Gestion des utilisateurs</h1>
        <span class="badge badge-critical" style="font-size:.9rem; padding:.4rem .8rem;">
            {{ $pending->count() }} en attente
        </span>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{-- ── COMPTES EN ATTENTE ── --}}
    <div class="content-section">
        <h2 style="display:flex; align-items:center; gap:.75rem;">
            ⏳ En attente de validation
            @if($pending->count() > 0)
                <span class="badge badge-critical">{{ $pending->count() }}</span>
            @endif
        </h2>

        @forelse($pending as $user)
            <div class="activity-item" style="flex-wrap:wrap; gap:1rem; padding:1rem; border:1px solid #f0c040; border-radius:8px; margin-bottom:1rem;">
                <div class="activity-details" style="flex:1; min-width:200px;">
                    <p><strong>{{ $user->full_name }}</strong>
                        <span class="badge badge-on_hold" style="margin-left:.5rem">pending</span>
                    </p>
                    <p class="activity-meta">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="activity-meta">📞 {{ $user->phone }}</p>
                    @endif
                    @if($user->department)
                        <p class="activity-meta">🏢 {{ $user->department }}</p>
                    @endif
                    <p class="activity-meta">Inscrit {{ $user->created_at->diffForHumans() }}</p>
                </div>

                {{-- Formulaire approbation --}}
                <form method="POST" action="{{ route('admin.users.approve', $user) }}"
                      style="display:flex; align-items:center; gap:.5rem; flex-wrap:wrap;">
                    @csrf
                    <select name="role" class="form-select" style="width:auto;" required>
                        <option value="">-- Rôle --</option>
                        <option value="client">Client</option>
                        <option value="developer">Développeur</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-small">✅ Approuver</button>
                </form>

                {{-- Formulaire refus --}}
                <form method="POST" action="{{ route('admin.users.reject', $user) }}"
                      style="display:flex; align-items:center; gap:.5rem; flex-wrap:wrap;">
                    @csrf
                    <input type="text" name="rejection_reason"
                           placeholder="Motif du refus (optionnel)"
                           style="flex:1; min-width:200px;">
                    <button type="submit" class="btn btn-danger btn-small"
                            onclick="return confirm('Refuser {{ $user->name }} ?')">
                        ❌ Refuser
                    </button>
                </form>
            </div>
        @empty
            <p class="empty-state">✅ Aucun compte en attente de validation.</p>
        @endforelse
    </div>

    {{-- ── COMPTES APPROUVÉS ── --}}
    <div class="content-section">
        <h2>✅ Comptes actifs ({{ $approved->count() }})</h2>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Département</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approved as $user)
                    <tr>
                        <td><strong>{{ $user->full_name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            {{-- Modifier le rôle inline --}}
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.role', $user) }}"
                                      style="display:flex; gap:.4rem; align-items:center;">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="form-select" style="width:auto; padding:.2rem .4rem;">
                                        <option value="client"    {{ $user->role == 'client'    ? 'selected' : '' }}>Client</option>
                                        <option value="developer" {{ $user->role == 'developer' ? 'selected' : '' }}>Développeur</option>
                                        <option value="manager"   {{ $user->role == 'manager'   ? 'selected' : '' }}>Manager</option>
                                        <option value="admin"     {{ $user->role == 'admin'     ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="btn-icon" title="Enregistrer">💾</button>
                                </form>
                            @else
                                <span class="badge badge-active">{{ $user->role }} (vous)</span>
                            @endif
                        </td>
                        <td>{{ $user->department ?? '—' }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      style="display:inline"
                                      onsubmit="return confirm('Supprimer {{ $user->name }} ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" title="Supprimer">🗑️</button>
                                </form>
                            @else
                                <span style="color:#999; font-size:.8rem">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-state">Aucun compte actif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── COMPTES REFUSÉS ── --}}
    @if($rejected->count() > 0)
        <div class="content-section">
            <h2>❌ Comptes refusés ({{ $rejected->count() }})</h2>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Motif</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rejected as $user)
                        <tr>
                            <td><strong>{{ $user->full_name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->rejection_reason ?? '—' }}</td>
                            <td>{{ $user->updated_at->format('d M Y') }}</td>
                            <td>
                                {{-- Ré-approuver --}}
                                <form method="POST" action="{{ route('admin.users.approve', $user) }}"
                                      style="display:inline-flex; gap:.4rem; align-items:center;">
                                    @csrf
                                    <select name="role" class="form-select" style="width:auto; padding:.2rem .4rem;" required>
                                        <option value="client">Client</option>
                                        <option value="developer">Développeur</option>
                                        <option value="manager">Manager</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-small">↩️ Ré-approuver</button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      style="display:inline"
                                      onsubmit="return confirm('Supprimer définitivement ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
