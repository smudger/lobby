<?php

namespace Tests\Feature;

use App\Domain\Models\Lobby;
use App\Domain\Repositories\GameRepository;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Events\InMemoryEventStore;
use App\Infrastructure\Http\Middleware\Authenticate;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use Inertia\Testing\AssertableInertia;
use Tests\Factories\LobbyFactory;
use Tests\TestCase;

class PlayGameTest extends TestCase
{
    private LobbyRepository $lobbyRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([Authenticate::class]);

        $this->lobbyRepository = new InMemoryLobbyRepository(new InMemoryEventStore());
        $this->app->instance(LobbyRepository::class, $this->lobbyRepository);
    }

    /** @test */
    public function a_player_can_a_game_in_a_lobby(): void
    {
        $lobbyFactory = new LobbyFactory();
        /** @var Lobby $lobby */
        $lobby = $lobbyFactory->create(id: $this->lobbyRepository->allocate());
        $this->lobbyRepository->save($lobby);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->app->make(GameRepository::class);
        $game = $gameRepository->all()[0];

        $response = $this->get('/lobbies/'.$lobby->id->__toString().'/games/'.$game->slug);

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Game/Show')
            ->has('lobby', fn (AssertableInertia $page) => $page->where('id', $lobby->id->__toString()))
            ->has('game', fn (AssertableInertia $page) => $page
                ->where('description', $game->description)
                ->where('name', $game->name)
                ->where('slug', $game->slug)
            )
        );
    }

    /** @test */
    public function a_player_cannot_play_a_game_that_does_not_exist(): void
    {
        $lobbyFactory = new LobbyFactory();
        /** @var Lobby $lobby */
        $lobby = $lobbyFactory->create(id: $this->lobbyRepository->allocate());
        $this->lobbyRepository->save($lobby);

        $response = $this->get('/lobbies/'.$lobby->id->__toString().'/games/a-game-that-does-not-exist');

        $response->assertNotFound();
    }

    /** @test */
    public function a_player_cannot_play_a_game_in_a_lobby_that_has_not_been_allocated(): void
    {
        $response = $this->get('/lobbies/AAAA/games');

        $response->assertNotFound();
    }
}
