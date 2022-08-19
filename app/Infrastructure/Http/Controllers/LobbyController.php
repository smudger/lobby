<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\CreateLobbyHandler;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LobbyController extends Controller
{
    public function show(string $id, LobbyRepository $repository): Response
    {
        try {
            $lobby = $repository->findById(LobbyId::fromString($id));
        } catch (LobbyNotAllocatedException) {
            abort(404);
        }

        return Inertia::render('Lobby/Show', ['lobby' => [
            'id' => $lobby->id->__toString(),
            'members' => collect($lobby->members())
                ->map(fn (Member $member) => [
                    'socket_id' => $member->socketId,
                    'name' => $member->name,
                ])
                ->toArray(),
        ]]);
    }

    public function create(): Response
    {
        return Inertia::render('Lobby/Create');
    }

    public function store(CreateLobbyHandler $handler): RedirectResponse
    {
        $lobby = $handler->execute();

        return redirect()->route('lobby.show', ['id' => $lobby->id]);
    }
}
