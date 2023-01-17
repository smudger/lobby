<?php

namespace App\Infrastructure\Http\Controllers;

use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
use App\Domain\Repositories\GameRepository;
use App\Domain\Repositories\LobbyRepository;
use Inertia\Inertia;
use Inertia\Response;

class GameController
{
    public function index(
        string $id,
        LobbyRepository $lobbyRepository,
        GameRepository $gameRepository,
    ): Response {
        try {
            $lobby = $lobbyRepository->findById(LobbyId::fromString($id));
        } catch (LobbyNotAllocatedException) {
            abort(404);
        }

        return Inertia::render('Game/Index', [
            'lobby' => [
                'id' => $lobby->id->__toString(),
                'members' => collect($lobby->members())
                    ->map(fn (Member $member) => [
                        'name' => $member->name,
                    ])
                    ->toArray(),
            ],
            'games' => $gameRepository->all(),
        ]);
    }
}
