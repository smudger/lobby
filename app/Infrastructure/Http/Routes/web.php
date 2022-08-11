<?php

use App\Domain\Events\MemberJoinedLobby;
use App\Infrastructure\Http\Controllers\LobbyController;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home');

Route::post('/lobbies', [LobbyController::class, 'store'])->name('lobby.store');
Route::get('/lobbies/{id}', [LobbyController::class, 'show'])->name('lobby.show');

Route::post('/lobbies/{id}/members', function (string $id) {
    Event::dispatch(new MemberJoinedLobby($id));

    return redirect()->route('lobby.show', ['id' => $id]);
})->name('lobby.members.create');
