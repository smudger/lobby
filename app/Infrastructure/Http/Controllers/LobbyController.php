<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\CreateLobbyHandler;
use Inertia\Inertia;
use Inertia\Response;

class LobbyController extends Controller
{
    public function store(CreateLobbyHandler $handler): Response
    {
        $lobby = $handler->execute();

        return Inertia::render('Lobby/Show', ['code' => $lobby->id->__toString()]);
    }
}
