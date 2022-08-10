<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Exceptions\IdGenerationException;
use App\Domain\Exceptions\LobbyNotFoundException;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;
use App\Domain\Repositories\LobbyRepository;
use Illuminate\Support\Facades\DB;

class SqlLobbyRepository implements LobbyRepository
{
    public function nextId(): LobbyId
    {
        if (DB::table('lobbies')->whereNull('allocated_at')->doesntExist()) {
            throw new IdGenerationException('No more lobby IDs available.');
        }

        /** @var \stdClass */
        $lobbyObject = DB::table('lobbies')
            ->select('id')
            ->whereNull('allocated_at')
            ->inRandomOrder()
            ->first();

        return LobbyId::fromString($lobbyObject->id);
    }

    public function save(Lobby $lobby): void
    {
        DB::table('lobbies')
            ->where('id', $lobby->id->__toString())
            ->update([
                'allocated_at' => now(),
                'updated_at' => now(),
            ]);
    }

    public function findById(LobbyId $id): Lobby
    {
        if (DB::table('lobbies')->where('id', $id->__toString())->whereNotNull('allocated_at')->doesntExist()) {
            throw new LobbyNotFoundException('A lobby with the given ID could not be found.');
        }

        return new Lobby($id);
    }
}
