<?php

use App\Domain\Events\MemberJoinedLobby;
use App\Infrastructure\Http\Controllers\LobbyController;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Home');

Route::post('/lobbies', [LobbyController::class, 'store']);

Route::get('/lobbies/{id}', function (string $id) {
    return Inertia::render('Lobby/Show', ['id' => $id]);
})->name('lobby.show');

Route::post('/lobbies/{id}/members', function (string $id) {
    Event::dispatch(new MemberJoinedLobby($id));

    return redirect()->route('lobby.show', ['id' => $id]);
})->name('lobby.members.create');
