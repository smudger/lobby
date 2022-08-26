<?php

namespace App\Infrastructure\Auth;

use App\Application\Auth\UserRepository;
use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class User extends Model implements AuthenticatableContract, UserFactory, HasSession, UserRepository
{
    use Authenticatable;

    protected $fillable = [
        'lobby_id',
        'member_id',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'member_id' => 'integer',
    ];

    public function createFromLobbyMember(Lobby $lobby, Member $member): self
    {
        return $this->createFromRaw([
            'lobby_id' => $lobby->id->__toString(),
            'member_id' => $member->id,
        ]);
    }

    public function createFromRaw(array $parameters): self
    {
        return parent::create($parameters);
    }

    public function login(Session $session): void
    {
        Auth::login($this, remember: true);

        $session->regenerate();
    }

    public function logout(Session $session): void
    {
        $userId = Auth::id();

        Auth::logout();

        $session->invalidate();

        $session->regenerateToken();

        self::find($userId)->delete();
    }

    public function deleteByLobbyIdAndMemberId(string $lobbyId, int $memberId): void
    {
        self::query()
            ->where([
                'lobby_id' => $lobbyId,
                'member_id' => $memberId,
            ])
            ->delete();
    }
}
