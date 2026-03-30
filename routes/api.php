<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth:sanctum'])->group(function () {


    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

});
