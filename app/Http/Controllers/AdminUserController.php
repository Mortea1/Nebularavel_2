<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminUserController extends Controller
{
    // Vérifie que l'user est admin, sinon 403
    private function requireAdmin(): void
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès réservé aux administrateurs.');
        }
    }

    // ─── Liste complète des utilisateurs ────────────────────────
    public function index()
    {
        $this->requireAdmin();

        $pending  = User::where('status', 'pending')->latest()->get();
        $approved = User::where('status', 'approved')->latest()->get();
        $rejected = User::where('status', 'rejected')->latest()->get();

        return view('admin.users', compact('pending', 'approved', 'rejected'));
    }

    // ─── Approuver un compte ─────────────────────────────────────
    public function approve(Request $request, User $user)
    {
        $this->requireAdmin();

        $request->validate([
            'role' => 'required|in:admin,manager,developer,client',
        ]);

        $user->update([
            'status'           => 'approved',
            'role'             => $request->role,
            'rejection_reason' => null,
        ]);

        return back()->with('success', "Compte de {$user->name} approuvé avec le rôle « {$request->role} ».");
    }

    // ─── Refuser un compte ───────────────────────────────────────
    public function reject(Request $request, User $user)
    {
        $this->requireAdmin();

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $user->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', "Compte de {$user->name} refusé.");
    }

    // ─── Modifier le rôle d'un utilisateur approuvé ─────────────
    public function updateRole(Request $request, User $user)
    {
        $this->requireAdmin();

        $request->validate([
            'role' => 'required|in:admin,manager,developer,client',
        ]);

        // Empêcher de se rétrograder soi-même
        if ($user->id === auth()->id()) {
            return back()->withErrors(['role' => 'Vous ne pouvez pas modifier votre propre rôle.']);
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "Rôle de {$user->name} mis à jour.");
    }

    // ─── Supprimer un utilisateur ────────────────────────────────
    public function destroy(User $user)
    {
        $this->requireAdmin();

        if ($user->id === auth()->id()) {
            return back()->withErrors(['delete' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $user->delete();

        return back()->with('success', "Utilisateur supprimé.");
    }
}
