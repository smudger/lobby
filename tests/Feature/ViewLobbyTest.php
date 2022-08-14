<?php

namespace Tests\Feature;

use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use Inertia\Testing\AssertableInertia;
use Tests\Factories\LobbyFactory;
use Tests\TestCase;

class ViewLobbyTest extends TestCase
{
    /** @test */
    public function a_player_can_view_a_lobby_that_has_been_allocated(): void
    {
        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $lobbyFactory = new LobbyFactory();

        /** @var Lobby $lobby */
        $lobby = $lobbyFactory->create(id: $repository->allocate());

        $repository->save($lobby);

        $response = $this->get('/lobbies/'.$lobby->id->__toString());

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Lobby/Show')
            ->has('lobby', fn (AssertableInertia $page) => $page
                ->where('id', $lobby->id->__toString())
                ->where('members', collect($lobby->members())
                    ->map(fn (Member $member) => [
                        'socket_id' => $member->socketId,
                        'name' => $member->name,
                    ])
                    ->toArray()
                )
           )
        );
    }

    /** @test */
    public function a_player_cannot_view_a_lobby_that_has_not_been_allocated(): void
    {
        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $response = $this->get('/lobbies/AAAA');

        $response->assertNotFound();
    }
}
