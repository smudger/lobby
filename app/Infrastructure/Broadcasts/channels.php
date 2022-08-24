<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('lobby.{lobbyId}', fn ($user, $lobbyId) => $user->lobby_id === $lobbyId);
