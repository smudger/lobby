<?php

namespace Tests\Feature;

use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use Tests\TestCase;

class JoinLobbyTest extends TestCase
{
    /** @test */
    public function a_player_can_join_a_lobby(): void
    {
        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $lobby = $repository->allocate();

        $response = $this->post('/members', [
            'lobby_id' => $lobby->id->__toString(),
            'name' => 'Ayesha Nicole',
        ]);

        $response->assertRedirect(route('lobby.show', ['id' => $lobby->id]));
    }

    /** @test */
    public function a_player_cannot_join_a_lobby_that_has_not_been_allocated(): void
    {
        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $response = $this->post('/members', [
            'lobby_id' => 'AAAA',
            'name' => 'Ayesha Nicole',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'lobby_id' => 'The selected lobby code is invalid.',
        ]);
    }
}
