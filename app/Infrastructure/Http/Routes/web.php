<?php

use App\Infrastructure\Http\Controllers\FeedController;
use App\Infrastructure\Http\Controllers\GameController;
use App\Infrastructure\Http\Controllers\LobbyController;
use App\Infrastructure\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::inertia('/', 'Public/Home')->name('home');

    Route::get('/lobbies/create', [LobbyController::class, 'create'])->name('lobby.create');
    Route::post('/lobbies', [LobbyController::class, 'store'])->name('lobby.store');

    Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/lobbies/{id}', [LobbyController::class, 'show'])->name('lobby.show');
    Route::get('/lobbies/{id}/members', [MemberController::class, 'index'])->name('members.index');
    Route::delete('/lobbies/{id}/members/{memberId}', [MemberController::class, 'destroy'])->name('members.destroy');
    Route::get('/lobbies/{id}/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/lobbies/{id}/feed', [FeedController::class, 'index'])->name('feed.index');

    Route::delete('/members/me', [MemberController::class, 'destroyMe'])->name('members.destroyMe');
});
