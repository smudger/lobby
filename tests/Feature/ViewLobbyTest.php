<?php

namespace Tests\Feature;

use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ViewLobbyTest extends TestCase
{
    /** @test */
    public function a_player_can_view_a_lobby_that_has_been_allocated(): void
    {
        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $lobby = $repository->allocate();

        $response = $this->get('/lobbies/'.$lobby->id->__toString());

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Lobby/Show')
            ->where('id', $lobby->id->__toString())
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
