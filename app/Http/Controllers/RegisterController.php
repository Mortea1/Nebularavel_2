<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        // Si déjà connecté → dashboard
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'nullable|string|max:50',
            'department' => 'nullable|string|max:255',
            'password'   => 'required|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'name'       => $validated['first_name'] . ' ' . $validated['last_name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
            'password'   => Hash::make($validated['password']),
            'role'       => 'pending',   // rôle neutre jusqu'à validation
            'status'     => 'pending',   // bloqué jusqu'à approbation admin
        ]);

        return redirect()->route('home')
            ->with('success', 'Votre demande de compte a été envoyée. Un administrateur va examiner votre dossier.');
    }
}
