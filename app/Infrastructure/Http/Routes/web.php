<?php

use App\Infrastructure\Http\Controllers\FeedController;
use App\Infrastructure\Http\Controllers\GameController;
use App\Infrastructure\Http\Controllers\LobbyController;
use App\Infrastructure\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Public/Home');

Route::post('/lobbies', [LobbyController::class, 'store'])->name('lobby.store');
Route::get('/lobbies/{id}', [LobbyController::class, 'show'])->name('lobby.show');

Route::get('/lobbies/{id}/members', [MemberController::class, 'index'])->name('members.index');
Route::post('/members', [MemberController::class, 'store'])->name('members.store');

Route::get('/lobbies/{id}/games', [GameController::class, 'index'])->name('games.index');

Route::get('/lobbies/{id}/feed', [FeedController::class, 'index'])->name('feed.index');
