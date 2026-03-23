<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Project;
use App\Models\User;
use App\Models\Tp;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $tickets = Ticket::with(['project', 'assignedUser'])->latest()->get();
        } else {
            $projectIds = $user->projects()->pluck('projects.id');

            $tickets = Ticket::with(['project', 'assignedUser'])
                ->whereIn('project_id', $projectIds)
                ->latest()
                ->get();
        }

        $projects = $user->role === 'admin'
            ? Project::all()
            : $user->projects;

        $users = User::all();

        return view('tickets', compact('tickets', 'projects', 'users'));
    }

    public function create()
    {
        $user = auth()->user();

        $projects = $user->role === 'admin'
            ? Project::orderBy('name')->get()
            : $user->projects()->orderBy('name')->get();

        $users = collect();

        return view('ticket-create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'project_id'  => 'nullable|exists:projects,id',
            'priority'    => 'nullable|string',
            'status'      => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'type'        => 'nullable|string',
            'category'    => 'nullable|string',
            'description' => 'nullable|string',
            'steps'       => 'nullable|string',
            'expected'    => 'nullable|string',
            'in_contract' => 'nullable|boolean',
        ]);

        // Vérifier que l'user est membre du projet sélectionné
        if ($validated['project_id'] ?? null) {
            $project = Project::findOrFail($validated['project_id']);
            if (auth()->user()->role !== 'admin' && !$project->users->contains(auth()->id())) {
                abort(403, 'Vous n\'êtes pas membre de ce projet.');
            }

            // Vérifier que l'assigné est bien membre du projet
            if ($validated['assigned_to'] ?? null) {
                if (!$project->users->contains($validated['assigned_to'])) {
                    return back()
                        ->withErrors(['assigned_to' => 'Cet utilisateur n\'est pas membre du projet.'])
                        ->withInput();
                }
            }
        }

        $validated['creator'] = auth()->user()->name;
        $ticket = Ticket::create($validated);

        return redirect()->route('ticket-detail', $ticket)
                         ->with('success', 'Ticket créé avec succès.');
    }

    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        $ticket->load(['project', 'assignedUser', 'tps.user', 'validations.user']);

        $users = $ticket->project
            ? $ticket->project->users
            : (auth()->user()->role === 'admin' ? User::all() : collect());

        return view('ticket-detail', compact('ticket', 'users'));
    }

    public function edit(Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $user = auth()->user();

        $projects = $user->role === 'admin'
            ? Project::orderBy('name')->get()
            : $user->projects()->orderBy('name')->get();

        $users = $ticket->project
            ? $ticket->project->users
            : ($user->role === 'admin' ? User::all() : collect());

        return view('ticket-edit', compact('ticket', 'projects', 'users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'project_id'  => 'nullable|exists:projects,id',
            'priority'    => 'nullable|string',
            'status'      => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'type'        => 'nullable|string',
            'category'    => 'nullable|string',
            'description' => 'nullable|string',
            'steps'       => 'nullable|string',
            'expected'    => 'nullable|string',
            'in_contract' => 'nullable|boolean',
        ]);

        // Vérifier que l'assigné est membre du projet
        $projectId = $validated['project_id'] ?? $ticket->project_id;
        if ($projectId && ($validated['assigned_to'] ?? null)) {
            $project = Project::find($projectId);
            if ($project && auth()->user()->role !== 'admin'
                && !$project->users->contains($validated['assigned_to'])) {
                return back()
                    ->withErrors(['assigned_to' => 'Cet utilisateur n\'est pas membre du projet.'])
                    ->withInput();
            }
        }

        $ticket->update($validated);

        return redirect()->route('ticket-detail', $ticket)
                         ->with('success', 'Ticket mis à jour.');
    }

    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket);

        $ticket->delete();
        return redirect()->route('tickets')->with('success', 'Ticket supprimé.');
    }

    // ─── Temps passé ────────────────────────────────────────────
    public function addTime(Request $request, Ticket $ticket)
    {
        Gate::authorize('addTime', $ticket);

        $request->validate([
            'time'    => 'required|integer|min:1',
            'comment' => 'nullable|string',
        ]);

        Tp::create([
            'ticket_id' => $ticket->id,
            'user_id'   => auth()->id(),
            'time'      => $request->time,
            'comment'   => $request->comment,
        ]);

        $ticket->increment('time_spent', $request->time);

        return back()->with('success', 'Temps ajouté.');
    }

    // ─── Validation ─────────────────────────────────────────────
    public function addValidation(Request $request, Ticket $ticket)
    {
        Gate::authorize('addValidation', $ticket);

        $request->validate([
            'decision' => 'required|in:approved,rejected,pending',
            'comment'  => 'nullable|string',
        ]);

        Validation::updateOrCreate(
            ['ticket_id' => $ticket->id, 'user_id' => auth()->id()],
            ['decision' => $request->decision, 'comment' => $request->comment]
        );

        return back()->with('success', 'Validation enregistrée.');
    }
}
