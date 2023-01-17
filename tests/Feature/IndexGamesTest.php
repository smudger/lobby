<?php

namespace Tests\Feature;

use App\Domain\Models\Game;
use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use App\Domain\Repositories\GameRepository;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Events\InMemoryEventStore;
use App\Infrastructure\Http\Middleware\Authenticate;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use Inertia\Testing\AssertableInertia;
use Tests\Factories\LobbyFactory;
use Tests\TestCase;

class IndexGamesTest extends TestCase
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
    public function a_player_can_index_all_the_games_they_can_play_in_a_lobby(): void
    {
        $lobbyFactory = new LobbyFactory();
        /** @var Lobby $lobby */
        $lobby = $lobbyFactory->create(id: $this->lobbyRepository->allocate());
        $this->lobbyRepository->save($lobby);
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->app->make(GameRepository::class);

        $response = $this->get('/lobbies/'.$lobby->id->__toString().'/games');

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Game/Index')
            ->has('lobby', fn (AssertableInertia $page) => $page
                ->where('id', $lobby->id->__toString())
                ->where('members', collect($lobby->members())
                    ->map(fn (Member $member) => [
                        'name' => $member->name,
                    ])
                    ->toArray()
                )
           )
            ->where('games', collect($gameRepository->all())
                    ->map(fn (Game $game) => [
                        'description' => $game->description,
                        'name' => $game->name,
                        'slug' => $game->slug,
                    ])
                    ->all()
           )
        );
    }

    /** @test */
    public function a_player_cannot_index_games_in_a_lobby_that_has_not_been_allocated(): void
    {
        $response = $this->get('/lobbies/AAAA/games');

        $response->assertNotFound();
    }
}
