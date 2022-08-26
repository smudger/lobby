<?php

namespace Tests\Feature;

use App\Domain\Models\Lobby;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Auth\User;
use App\Infrastructure\Broadcasts\DomainBroadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class LeaveLobbyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_member_can_leave_a_lobby(): void
    {
        Event::fake();

        /** @var LobbyRepository $repository */
        $repository = $this->app->make(LobbyRepository::class);

        $lobby = new Lobby($repository->allocate());
        $lobby->createMember('Ayesha Nicole');
        $repository->save($lobby);

        $user = (new User())->createFromLobbyMember($lobby, $lobby->members()[0]);

        $response = $this->actingAs($user)
            ->delete('/members/me');

        $response->assertRedirect(route('home'));
        Assert::assertFalse(Auth::check());
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
        $updatedLobby = $repository->findById($lobby->id);
        Assert::assertCount(0, $updatedLobby->members());
        Event::assertDispatched(DomainBroadcast::class, function (DomainBroadcast $broadcast) {
            return $broadcast->broadcastAs() === 'member_left_lobby';
        });
    }
}
