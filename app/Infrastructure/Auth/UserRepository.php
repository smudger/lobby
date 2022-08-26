<?php

namespace App\Infrastructure\Auth;

interface UserRepository
{
    public function deleteByLobbyIdAndMemberId(string $lobbyId, int $memberId): void;
}
