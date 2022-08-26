<?php

namespace Tests\Feature;

use App\Domain\Models\Lobby;
use App\Domain\Repositories\LobbyRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class JoinLobbyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_player_can_join_a_lobby(): void
    {
        /** @var LobbyRepository $repository */
        $repository = $this->app->make(LobbyRepository::class);

        $lobby = new Lobby($repository->allocate());

        $response = $this->post('/members', [
            'lobby_id' => $lobby->id->__toString(),
            'name' => 'Ayesha Nicole',
        ]);

        $response->assertRedirect(route('lobby.show', ['id' => $lobby->id]));

        $updatedLobby = $repository->findById($lobby->id);

        Assert::assertCount(1, $updatedLobby->members());
        $member = $updatedLobby->members()[0];

        Assert::assertEquals('Ayesha Nicole', $member->name);
        Assert::assertEquals(1, $member->id);
    }

    /** @test */
    public function a_player_cannot_join_a_lobby_that_has_not_been_allocated(): void
    {
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
