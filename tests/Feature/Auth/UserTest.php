<?php

namespace Tests\Feature\Auth;

use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

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
            'member_id' => 1,
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
    public function it_can_logout_a_user_from_a_given_session(): void
    {
        /** @var LobbyRepository $lobbyRepository */
        $lobbyRepository = $this->app->make(LobbyRepository::class);

        $user = (new User())->createFromRaw([
            'lobby_id' => $lobbyRepository->allocate()->__toString(),
            'member_id' => 1,
        ]);
        $session = new Store('session', new ArraySessionHandler(120));
        $user->login($session);

        $oldSessionId = $session->getId();
        $oldSessionToken = $session->token();

        $user->logout($session);

        Assert::assertFalse(Auth::check());
        Assert::assertNotEquals($oldSessionId, $session->getId());
        Assert::assertNotEquals($oldSessionToken, $session->token());
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_create_a_new_user_from_a_lobby_member(): void
    {
        $lobbyId = $this->getLobbyRepository()->allocate();
        $lobby = new Lobby($lobbyId);
        $member = new Member(id: 1, name: 'Ayesha Nicole', joinedAt: Carbon::now());

        $user = (new User())->createFromLobbyMember($lobby, $member);

        Assert::assertEquals($lobbyId->__toString(), $user->lobby_id);
        Assert::assertEquals(1, $user->member_id);
    }

    /** @test */
    public function it_can_create_a_new_user_from_raw_parameters(): void
    {
        $lobbyId = $this->getLobbyRepository()->allocate();

        $user = (new User())->createFromRaw([
            'lobby_id' => $lobbyId->__toString(),
            'member_id' => 1,
        ]);

        Assert::assertEquals($lobbyId->__toString(), $user->lobby_id);
        Assert::assertEquals(1, $user->member_id);
    }

    /** @test */
    public function it_can_delete_a_user_by_its_lobby_id_and_member_id(): void
    {
        $lobbyId = $this->getLobbyRepository()->allocate();

        (new User())->createFromRaw([
            'lobby_id' => $lobbyId->__toString(),
            'member_id' => 1,
        ]);

        (new User())->deleteByLobbyIdAndMemberId($lobbyId->__toString(), 1);

        $this->assertDatabaseMissing('users', [
            'lobby_id' => $lobbyId->__toString(),
            'member_id' => 1,
        ]);
    }
}
