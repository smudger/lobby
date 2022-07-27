<?php

use App\Domain\Events\MemberJoinedLobby;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Home');

Route::get('/lobbies/{code}', function (string $code) {
    return Inertia::render('Lobby/Show', ['code' => $code]);
})->name('lobby.show');

Route::post('/lobbies/{code}/members', function (string $code) {
    Event::dispatch(new MemberJoinedLobby($code));

    return redirect()->route('lobby.show', ['code' => $code]);
})->name('lobby.members.create');
