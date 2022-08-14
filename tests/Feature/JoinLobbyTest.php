<?php

namespace Tests\Feature;

use App\Domain\Models\Lobby;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class JoinLobbyTest extends TestCase
{
    /** @test */
    public function a_player_can_join_a_lobby(): void
    {
        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $lobby = new Lobby($repository->allocate());

        $response = $this->post('/members', [
            'lobby_id' => $lobby->id->__toString(),
            'name' => 'Ayesha Nicole',
            'socket_id' => '123.456',
        ]);

        $response->assertRedirect(route('lobby.show', ['id' => $lobby->id]));

        $updatedLobby = $repository->findById($lobby->id);

        Assert::assertCount(1, $updatedLobby->members());
        $member = $updatedLobby->members()[0];

        Assert::assertEquals('123.456', $member->socketId);
        Assert::assertEquals('Ayesha Nicole', $member->name);
    }

    /** @test */
    public function a_player_cannot_join_a_lobby_that_has_not_been_allocated(): void
    {
        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $response = $this->post('/members', [
            'lobby_id' => 'AAAA',
            'name' => 'Ayesha Nicole',
            'socket_id' => '123.456',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'lobby_id' => 'The selected lobby code is invalid.',
        ]);
    }
}
