<?php

namespace Tests\Feature\Auth;

use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Auth\User;
use App\Infrastructure\Auth\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function getFactory(): UserFactory
    {
        return new User();
    }

    protected function getLobbyRepository(): LobbyRepository
    {
        /** @var LobbyRepository */
        return $this->app->make(LobbyRepository::class);
    }

    /** @test */
    public function it_can_login_a_user_to_a_given_session(): void
    {
        Auth::spy();

        /** @var LobbyRepository $lobbyRepository */
        $lobbyRepository = $this->app->make(LobbyRepository::class);

        $user = (new User())->createFromRaw([
            'lobby_id' => $lobbyRepository->allocate()->__toString(),
            'name' => 'Ayesha Nicole',
        ]);
        $session = new Store('session', new ArraySessionHandler(120));
        $oldSessionId = $session->getId();

        $user->login($session);

        Assert::assertNotEquals($oldSessionId, $session->getId());
        Auth::shouldHaveReceived('login')
            ->once()
            ->withArgs(fn (User $arg1, bool $remember) => $remember
                    && $arg1->is($user));
    }

    /** @test */
    public function it_can_create_a_new_user_from_a_lobby_member(): void
    {
        $lobbyId = $this->getLobbyRepository()->allocate();
        $lobby = new Lobby($lobbyId);
        $member = new Member('Ayesha Nicole');

        /** @var User $user */
        $user = $this->getFactory()->createFromLobbyMember($lobby, $member);

        Assert::assertEquals($lobbyId->__toString(), $user->lobby_id);
        Assert::assertEquals('Ayesha Nicole', $user->name);
    }

    /** @test */
    public function it_can_create_a_new_user_from_raw_parameters(): void
    {
        $lobbyId = $this->getLobbyRepository()->allocate();

        /** @var User $user */
        $user = $this->getFactory()->createFromRaw([
            'lobby_id' => $lobbyId->__toString(),
            'name' => 'Ayesha Nicole',
        ]);

        Assert::assertEquals($lobbyId->__toString(), $user->lobby_id);
        Assert::assertEquals('Ayesha Nicole', $user->name);
    }
}
