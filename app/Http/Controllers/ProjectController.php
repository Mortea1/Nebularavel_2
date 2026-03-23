<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $projects = Project::with(['users', 'contract'])->withCount('tickets')->get();
        } else {
            $projects = $user->projects()
                ->with(['users', 'contract'])
                ->withCount('tickets')
                ->get();
        }

        return view('projects', compact('projects'));
    }

    public function show(Project $project)
    {
        Gate::authorize('view', $project);

        $project->load(['users', 'contract', 'tickets.assignedUser']);
        return view('project-detail', compact('project'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('project-create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'client'   => 'nullable|string|max:255',
            'status'   => 'nullable|string',
            'progress' => 'nullable|integer|min:0|max:100',
            'deadline' => 'nullable|date',
            'team'     => 'nullable|string|max:255',
        ]);

        $project = Project::create($validated);

        // Membres — s'assurer que le créateur est toujours membre
        $userIds = $request->input('user_ids', []);
        if (!in_array(auth()->id(), $userIds) && auth()->user()->role !== 'admin') {
            $userIds[] = auth()->id();
        }
        $project->users()->sync($userIds);

        // Contrat
        if ($request->filled('hours_included') || $request->filled('hourly_rate')) {
            Contract::create([
                'project_id'     => $project->id,
                'hours_included' => $request->hours_included,
                'hourly_rate'    => $request->hourly_rate,
                'start_date'     => $request->start_date,
                'end_date'       => $request->end_date,
            ]);
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projet créé avec succès.');
    }

    public function edit(Project $project)
    {
        Gate::authorize('update', $project);

        $users = User::orderBy('name')->get();
        $project->load(['users', 'contract']);
        return view('project-edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'client'   => 'nullable|string|max:255',
            'status'   => 'nullable|string',
            'progress' => 'nullable|integer|min:0|max:100',
            'deadline' => 'nullable|date',
            'team'     => 'nullable|string|max:255',
        ]);

        $project->update($validated);

        $project->users()->sync($request->input('user_ids', []));

        if ($request->filled('hours_included') || $request->filled('hourly_rate')) {
            $project->contract()->updateOrCreate(
                ['project_id' => $project->id],
                [
                    'hours_included' => $request->hours_included,
                    'hours_used'     => $request->input('hours_used', $project->contract->hours_used ?? 0),
                    'hourly_rate'    => $request->hourly_rate,
                    'start_date'     => $request->start_date,
                    'end_date'       => $request->end_date,
                ]
            );
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Projet mis à jour.');
    }

    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        $project->delete();
        return redirect()->route('projects')->with('success', 'Projet supprimé.');
    }

    // ─── Membres d'un projet (utilisé par le JS de ticket-create/edit) ───────
    public function members(Project $project)
    {
        Gate::authorize('view', $project);

        return response()->json(
            $project->users()->select('users.id', 'users.name', 'users.role')->get()
        );
    }
}
