@extends('layouts.app')

@section('title', 'Créer un ticket')

@section('content')
    <header class="page-header">
        <div>
            <a href="{{ route('tickets') }}" class="back-link">← Retour aux tickets</a>
            <h1>Créer un nouveau ticket</h1>
        </div>
    </header>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="form-container">
        <form class="ticket-form" method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="content-section">
                <h2>Informations générales</h2>

                <div class="form-group">
                    <label for="ticket-title">Titre du ticket *</label>
                    <input type="text" id="ticket-title" name="title"
                           value="{{ old('title') }}"
                           placeholder="Ex: Bug sur la page de connexion">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ticket-project">Projet *</label>
                        <select id="ticket-project" name="project_id">
                            <option value="">Sélectionnez un projet</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id', request('project_id')) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-hint">Seuls les membres du projet sélectionné pourront être assignés.</small>
                    </div>

                    <div class="form-group">
                        <label for="ticket-type">Type *</label>
                        <select id="ticket-type" name="type">
                            <option value="">Sélectionnez un type</option>
                            <option value="bug"         {{ old('type') == 'bug'         ? 'selected' : '' }}>Bug</option>
                            <option value="feature"     {{ old('type') == 'feature'     ? 'selected' : '' }}>Nouvelle fonctionnalité</option>
                            <option value="improvement" {{ old('type') == 'improvement' ? 'selected' : '' }}>Amélioration</option>
                            <option value="task"        {{ old('type') == 'task'        ? 'selected' : '' }}>Tâche</option>
                            <option value="maintenance" {{ old('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ticket-priority">Priorité *</label>
                        <select id="ticket-priority" name="priority">
                            <option value="">Sélectionnez une priorité</option>
                            <option value="low"      {{ old('priority') == 'low'      ? 'selected' : '' }}>Basse</option>
                            <option value="medium"   {{ old('priority','medium') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                            <option value="high"     {{ old('priority') == 'high'     ? 'selected' : '' }}>Haute</option>
                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ticket-assignee">Assigner à</label>
                        <select id="ticket-assignee" name="assigned_to" disabled>
                            <option value="">— Sélectionnez d'abord un projet —</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ticket-category">Catégorie</label>
                    <select id="ticket-category" name="category">
                        <option value="">Sélectionnez une catégorie</option>
                        <option value="frontend"      {{ old('category') == 'frontend'      ? 'selected' : '' }}>Interface utilisateur</option>
                        <option value="backend"       {{ old('category') == 'backend'       ? 'selected' : '' }}>Backend</option>
                        <option value="database"      {{ old('category') == 'database'      ? 'selected' : '' }}>Base de données</option>
                        <option value="performance"   {{ old('category') == 'performance'   ? 'selected' : '' }}>Performance</option>
                        <option value="security"      {{ old('category') == 'security'      ? 'selected' : '' }}>Sécurité</option>
                        <option value="devops"        {{ old('category') == 'devops'        ? 'selected' : '' }}>DevOps</option>
                        <option value="documentation" {{ old('category') == 'documentation' ? 'selected' : '' }}>Documentation</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ticket-description">Description *</label>
                    <textarea id="ticket-description" name="description" rows="6"
                              placeholder="Décrivez le problème ou la demande en détail...">{{ old('description') }}</textarea>
                    <small class="form-hint">Soyez aussi précis que possible pour faciliter la résolution</small>
                </div>

                <div class="form-group">
                    <label for="ticket-steps">Étapes pour reproduire (optionnel)</label>
                    <textarea id="ticket-steps" name="steps" rows="4"
                              placeholder="1. Aller sur la page...&#10;2. Cliquer sur...&#10;3. Observer...">{{ old('steps') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="ticket-expected">Comportement attendu (optionnel)</label>
                    <textarea id="ticket-expected" name="expected" rows="3"
                              placeholder="Décrivez ce qui devrait se passer...">{{ old('expected') }}</textarea>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="in_contract" value="1" {{ old('in_contract') ? 'checked' : '' }}>
                        Ce ticket est inclus dans le contrat
                    </label>
                </div>
            </div>

            <div class="content-section">
                <h2>Pièces jointes</h2>
                <div class="file-upload-area" id="upload-area">
                    <input type="file" id="ticket-files" name="files[]" multiple style="display: none;">
                    <label for="ticket-files" class="file-upload-label">
                        <span class="upload-icon">📎</span>
                        <span>Cliquez pour ajouter des fichiers</span>
                        <small>ou glissez-déposez vos fichiers ici</small>
                    </label>
                </div>
                <div id="file-list" class="file-list"></div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large">Créer le ticket</button>
                <a href="{{ route('tickets') }}" class="btn btn-secondary btn-large">Annuler</a>
            </div>
        </form>
    </div>

    <div id="toast" class="toast"></div>
@endsection

@push('scripts')
<script>
    // ── Chargement dynamique des membres selon le projet ──────────
    const projectSelect  = document.getElementById('ticket-project');
    const assigneeSelect = document.getElementById('ticket-assignee');
    const oldAssignedTo  = "{{ old('assigned_to') }}";
    const oldProjectId   = "{{ old('project_id', request('project_id')) }}";

    async function loadMembers(projectId) {
        if (!projectId) {
            assigneeSelect.innerHTML = '<option value="">— Sélectionnez d\'abord un projet —</option>';
            assigneeSelect.disabled = true;
            return;
        }

        assigneeSelect.disabled = true;
        assigneeSelect.innerHTML = '<option value="">Chargement...</option>';

        try {
            const res  = await fetch(`/api/projects/${projectId}/members`);
            const members = await res.json();

            assigneeSelect.innerHTML = '<option value="">Non assigné</option>';
            members.forEach(user => {
                const opt = document.createElement('option');
                opt.value = user.id;
                opt.textContent = user.name + (user.role ? ` (${user.role})` : '');
                if (String(user.id) === String(oldAssignedTo)) opt.selected = true;
                assigneeSelect.appendChild(opt);
            });

            assigneeSelect.disabled = false;
        } catch (e) {
            assigneeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    }

    projectSelect.addEventListener('change', () => loadMembers(projectSelect.value));

    // Chargement initial si projet déjà sélectionné (old() ou query string)
    if (oldProjectId) {
        projectSelect.value = oldProjectId;
        loadMembers(oldProjectId);
    }

    // ── Upload fichiers ───────────────────────────────────────────
    const uploadArea = document.getElementById('upload-area');
    const fileInput  = document.getElementById('ticket-files');
    const fileList   = document.getElementById('file-list');
    let storedFiles  = new DataTransfer();

    uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        Array.from(e.dataTransfer.files).forEach(f => storedFiles.items.add(f));
        fileInput.files = storedFiles.files;
        renderFiles();
    });

    fileInput.addEventListener('change', () => {
        Array.from(fileInput.files).forEach(f => storedFiles.items.add(f));
        fileInput.files = storedFiles.files;
        renderFiles();
    });

    function renderFiles() {
        fileList.innerHTML = '';
        Array.from(storedFiles.files).forEach((file, i) => {
            const item = document.createElement('div');
            item.className = 'file-item';
            item.innerHTML = `<span>📎 ${file.name}</span>
                <span class="file-size">(${(file.size/1024).toFixed(1)} KB)
                <button type="button" class="remove-file" data-index="${i}">❌</button></span>`;
            fileList.appendChild(item);
        });
        document.querySelectorAll('.remove-file').forEach(btn =>
            btn.addEventListener('click', () => {
                const dt = new DataTransfer();
                Array.from(storedFiles.files).forEach((f, i) => { if (i != btn.dataset.index) dt.items.add(f); });
                storedFiles = dt;
                fileInput.files = storedFiles.files;
                renderFiles();
            })
        );
    }
</script>
@endpush
