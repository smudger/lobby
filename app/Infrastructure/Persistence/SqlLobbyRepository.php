<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Exceptions\LobbyAllocationException;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Exceptions\NoMoreLobbiesException;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;
use App\Domain\Repositories\LobbyRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class SqlLobbyRepository implements LobbyRepository
{
    public function findById(LobbyId $id): Lobby
    {
        if (DB::table('lobbies')->where('id', $id->__toString())->doesntExist()) {
            throw new LobbyNotAllocatedException();
        }

        return new Lobby($id);
    }

    public function allocate(): Lobby
    {
        /** @var ?\stdClass */
        $row = DB::table('lobby_ids')
            ->select('id')
            ->whereNotIn('id', fn (Builder $query) => $query
                ->select('id')
                ->from('lobbies')
            )
            ->inRandomOrder()
            ->limit(1)
            ->first();

        if (is_null($row)) {
            throw new NoMoreLobbiesException();
        }

        try {
            DB::table('lobbies')
                ->insert([
                    'id' => $row->id,
                    'allocated_at' => now(),
                ]);
        } catch (QueryException $e) {
            throw new LobbyAllocationException(previous: $e);
        }

        return new Lobby(LobbyId::fromString($row->id));
    }
}
