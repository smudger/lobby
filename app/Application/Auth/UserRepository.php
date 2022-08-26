<?php

namespace App\Application\Auth;

interface UserRepository
{
    public function deleteByLobbyIdAndMemberId(string $lobbyId, int $memberId): void;
}
