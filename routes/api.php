<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth', 'check.account.status'])->group(function () {


    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

});
