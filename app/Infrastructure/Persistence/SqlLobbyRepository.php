<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Events\EventStore;
use App\Domain\Exceptions\LobbyAllocationException;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Exceptions\NoMoreLobbiesException;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

class SqlLobbyRepository implements LobbyRepository
{
    public function __construct(
        private readonly EventStore $eventStore,
    ) {
    }

    public function findById(LobbyId $id): Lobby
    {
        /** @var ?stdClass $row */
        $row = DB::table('lobbies')
            ->where('id', $id->__toString())
            ->first();

        if (is_null($row)) {
            throw new LobbyNotAllocatedException();
        }

        /** @var array<string, mixed>[] $rawMembers */
        $rawMembers = json_decode($row->members, true);

        /** @var Member[] $members */
        $members = collect($rawMembers)
            ->map(fn (array $raw) => new Member(
                id: $raw['id'],
                name: $raw['name'],
                joinedAt: Carbon::parse($raw['joinedAt']),
            ))
            ->toArray();

        return new Lobby(
            id: $id,
            members: $members,
        );
    }

    public function allocate(): LobbyId
    {
        /** @var ?stdClass */
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
                    'members' => json_encode([]),
                ]);
        } catch (QueryException $e) {
            throw new LobbyAllocationException(previous: $e);
        }

        return LobbyId::fromString($row->id);
    }

    public function save(Lobby $lobby): void
    {
        $this->checkLobbyIdIsAllocated($lobby->id);

        DB::table('lobbies')
            ->where('id', $lobby->id->__toString())
            ->update(['members' => $lobby->members()]);

        $this->eventStore->addAll($lobby->events());
        $lobby->flushEvents();
    }

    private function checkLobbyIdIsAllocated(LobbyId $id): void
    {
        if (DB::table('lobbies')->where('id', $id->__toString())->doesntExist()) {
            throw new LobbyNotAllocatedException();
        }
    }
}
