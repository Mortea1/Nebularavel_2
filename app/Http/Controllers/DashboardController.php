<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\Tp;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_projects'    => $user->isAdmin()
                ? Project::count()
                : $user->projects()->count(),
            'open_tickets'      => $user->isAdmin()
                ? Ticket::where('status', 'open')->count()
                : Ticket::whereIn('project_id', $user->projects()->pluck('projects.id'))
                         ->where('status', 'open')->count(),
            'total_users'       => User::where('status', 'approved')->count(),
            'hours_this_month'  => Tp::whereMonth('created_at', now()->month)->sum('time'),
        ];

        // Comptes en attente — visible seulement par admin
        $pending_count = $user->isAdmin()
            ? User::where('status', 'pending')->count()
            : 0;

        $recent_tickets = $user->isAdmin()
            ? Ticket::with(['project', 'assignedUser'])->latest()->take(5)->get()
            : Ticket::with(['project', 'assignedUser'])
                ->whereIn('project_id', $user->projects()->pluck('projects.id'))
                ->latest()->take(5)->get();

        $projects = $user->isAdmin()
            ? Project::withCount('tickets')->latest()->take(5)->get()
            : $user->projects()->withCount('tickets')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'pending_count', 'recent_tickets', 'projects'));
    }
}
