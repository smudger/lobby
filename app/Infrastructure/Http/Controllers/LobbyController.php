<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\CreateLobbyHandler;
use Illuminate\Http\RedirectResponse;

class LobbyController extends Controller
{
    public function store(CreateLobbyHandler $handler): RedirectResponse
    {
        $lobby = $handler->execute();

        return redirect()->route('lobby.show', ['id' => $lobby->id]);
    }
}
