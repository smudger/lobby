<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Home');

Route::get('/lobbies/{code}', function (string $code) {
    return Inertia::render('Lobby/Show', ['code' => $code]);
})->name('lobby.show');

Route::post('/lobbies/{code}/members', function (string $code) {
    return redirect()->route('lobby.show', ['code' => $code]);
})->name('lobby.members.create');
