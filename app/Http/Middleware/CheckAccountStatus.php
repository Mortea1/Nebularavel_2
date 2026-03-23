<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->status === 'pending') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('home')
                    ->with('warning', 'Votre compte est en attente de validation par un administrateur.');
            }

            if ($user->status === 'rejected') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                $reason = $user->rejection_reason
                    ? ' Motif : ' . $user->rejection_reason
                    : '';
                return redirect()->route('home')
                    ->with('error', 'Votre demande de compte a été refusée.' . $reason);
            }
        }

        return $next($request);
    }
}
