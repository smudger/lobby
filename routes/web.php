<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Home');

Route::get('/lobbies/{code}', function (string $code) {
    return Inertia::render('Lobby/Show', ['code' => $code]);
});
