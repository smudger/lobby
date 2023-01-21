<?php

namespace App\Infrastructure\Http\Controllers;

use App\Domain\Exceptions\GameNotFoundException;
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

    public function show(
        string $id,
        string $slug,
        LobbyRepository $lobbyRepository,
        GameRepository $gameRepository,
    ): Response {
        try {
            $lobby = $lobbyRepository->findById(LobbyId::fromString($id));
        } catch (LobbyNotAllocatedException) {
            abort(404);
        }

        try {
            $game = $gameRepository->findBySlug($slug);
        } catch (GameNotFoundException) {
            abort(404);
        }

        return Inertia::render('Game/Show', [
            'lobby' => ['id' => $lobby->id->__toString()],
            'game' => [
                'description' => $game->description,
                'name' => $game->name,
                'slug' => $game->slug,
            ],
        ]);
    }
}
