<?php

namespace Tests\Feature;

use App\Domain\Models\Lobby;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Auth\User;
use App\Infrastructure\Broadcasts\DomainBroadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class KickMemberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_member_can_kick_another_member_from_a_lobby(): void
    {
        Event::fake();

        /** @var LobbyRepository $repository */
        $repository = $this->app->make(LobbyRepository::class);
        $lobby = new Lobby($repository->allocate());

        $lobby->createMember('Ayesha Nicole');
        $memberToKeep = $lobby->members()[0];
        $user = (new User())->createFromLobbyMember($lobby, $memberToKeep);

        $idToKick = $lobby->createMember('Kim Petras');

        $repository->save($lobby);

        $response = $this->actingAs($user)
            ->delete('/lobbies/'.$lobby->id->__toString().'/members/'.$idToKick);

        $response->assertRedirect(route('members.index', ['id' => $lobby->id]));
        $updatedLobby = $repository->findById($lobby->id);
        Assert::assertCount(1, $updatedLobby->members());
        Assert::assertTrue($updatedLobby->members()[0]->equals($memberToKeep));
        Event::assertDispatched(DomainBroadcast::class, function (DomainBroadcast $broadcast) {
            return $broadcast->broadcastAs() === 'member_left_lobby';
        });
    }
}
