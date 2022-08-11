<?php

use App\Infrastructure\Http\Controllers\LobbyController;
use App\Infrastructure\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home');

Route::post('/lobbies', [LobbyController::class, 'store'])->name('lobby.store');
Route::get('/lobbies/{id}', [LobbyController::class, 'show'])->name('lobby.show');

Route::post('/members', [MemberController::class, 'store'])->name('members.store');

Route::inertia('/test', 'Test');
