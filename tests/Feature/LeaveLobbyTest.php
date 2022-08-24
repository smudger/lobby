<?php

namespace Tests\Feature;

use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class LeaveLobbyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_member_can_leave_a_lobby(): void
    {
        /** @var LobbyRepository $repository */
        $repository = $this->app->make(LobbyRepository::class);

        $lobby = new Lobby($repository->allocate());
        $member = new Member('Ayesha Nicole');
        $lobby->addMember($member);
        $repository->save($lobby);

        $user = (new User())->createFromLobbyMember($lobby, $member);

        $response = $this->actingAs($user)
            ->delete('/members/me');

        $response->assertRedirect(route('home'));
        Assert::assertFalse(Auth::check());
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
        $updatedLobby = $repository->findById($lobby->id);
        Assert::assertCount(0, $updatedLobby->members());
    }
}
