<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminUserController;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/help', [PageController::class, 'help'])->name('help');

// ── Register custom ───────────────────────────────────────────
// NOTE : on surcharge la route 'register' de Breeze (désactivée dans auth.php)
Route::middleware('guest')->group(function () {
    Route::get('/register',  [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

/*
|--------------------------------------------------------------------------
| Protected routes — auth + compte approuvé
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.account.status'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::get('/projects',               [ProjectController::class, 'index'])->name('projects');
    Route::get('/projects/create',        [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects',              [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}',     [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit',[ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}',     [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}',  [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Tickets
    Route::get('/tickets',                     [TicketController::class, 'index'])->name('tickets');
    Route::get('/ticket-create',               [TicketController::class, 'create'])->name('ticket-create');
    Route::post('/tickets',                    [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/ticket-detail/{ticket}',      [TicketController::class, 'show'])->name('ticket-detail');
    Route::get('/tickets/{ticket}/edit',       [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}',            [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}',         [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::post('/tickets/{ticket}/time',      [TicketController::class, 'addTime'])->name('tickets.time');
    Route::post('/tickets/{ticket}/validation',[TicketController::class, 'addValidation'])->name('tickets.validation');

    // API membres projet
    Route::get('/api/projects/{project}/members', function (App\Models\Project $project) {
        $user = auth()->user();
        if ($user->role !== 'admin' && !$project->users->contains($user->id)) {
            abort(403);
        }
        return response()->json(
            $project->users()->select('users.id', 'users.name', 'users.role')->get()
        );
    })->name('api.project.members');

    // Profile & Settings
    Route::get('/profile',          [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/settings',         [ProfileController::class, 'index'])->name('settings');

    // ── Admin ─────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users',                 [AdminUserController::class, 'index'])->name('users');
        Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/reject',  [AdminUserController::class, 'reject'])->name('users.reject');
        Route::put('/users/{user}/role',     [AdminUserController::class, 'updateRole'])->name('users.role');
        Route::delete('/users/{user}',       [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

require __DIR__.'/auth.php';
