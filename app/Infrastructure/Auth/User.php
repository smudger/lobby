<?php

namespace App\Infrastructure\Auth;

use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class User extends Model implements AuthenticatableContract, UserFactory, HasSession
{
    use Authenticatable;

    protected $fillable = [
        'name',
        'lobby_id',
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function createFromLobbyMember(Lobby $lobby, Member $member): self
    {
        return $this->createFromRaw([
            'lobby_id' => $lobby->id->__toString(),
            'name' => $member->name,
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
}
