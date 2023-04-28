<?php

namespace Tests\Unit\Infrastructure\Persistence;

use App\Domain\Events\EventStore;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Assert;

trait LobbyRepositoryTestTrait
{
    abstract protected function getRepository(): LobbyRepository;

    abstract protected function getEventStore(): EventStore;

    abstract public function it_throws_an_exception_if_there_are_no_more_lobbies_available(): void;

    /** @test */
    public function it_doesnt_return_a_lobby_id_that_has_already_been_allocated(): void
    {
        $repository = $this->getRepository();

        $first = $repository->allocate();
        $second = $repository->allocate();
        $third = $repository->allocate();

        Assert::assertFalse($first->equals($second));
        Assert::assertFalse($first->equals($third));
        Assert::assertFalse($second->equals($third));
    }

    /** @test */
    public function it_can_allocate_a_lobby_id(): void
    {
        $repository = $this->getRepository();

        $lobbyId = $repository->allocate();

        Assert::assertTrue($lobbyId->equals($repository->findById($lobbyId)->id));
    }

    /** @test */
    public function it_throws_an_exception_when_searching_for_a_lobby_that_hasnt_been_allocated(): void
    {
        $repository = $this->getRepository();

        try {
            $repository->findById(LobbyId::fromString('AAAA'));

            Assert::fail('No exception thrown despite lobby not allocated.');
        } catch (LobbyNotAllocatedException $e) {
            Assert::assertEquals('The lobby with the given ID has not been allocated.', $e->getMessage());
        }
    }

    /** @test */
    public function it_saves_an_updated_lobby(): void
    {
        Carbon::setTestNow(now());

        $repository = $this->getRepository();

        $lobby = new Lobby($repository->allocate());

        $lobby->createMember('Ayesha Nicole');

        $repository->save($lobby);

        $updatedLobby = $repository->findById($lobby->id);

        Assert::assertCount(1, $updatedLobby->members());
        Assert::assertTrue($updatedLobby->members()[0]->equals(new Member(id: 1, name: 'Ayesha Nicole', joinedAt: Carbon::now())));
    }

    /** @test */
    public function it_saves_the_events_on_the_lobby(): void
    {
        $repository = $this->getRepository();

        $lobby = new Lobby($repository->allocate());

        $lobby->createMember('Ayesha Nicole');
        $lobby->removeMember(1);

        $repository->save($lobby);

        Assert::assertCount(0, $lobby->events());
        Assert::assertNotEmpty($this->getEventStore()->findAllByAggregateId($lobby->id));
    }

    /** @test */
    public function it_throws_an_exception_when_saving_a_lobby_that_has_not_been_allocated(): void
    {
        $repository = $this->getRepository();

        $lobby = new Lobby(LobbyId::fromString('AAAA'));

        try {
            $repository->save($lobby);

            Assert::fail('No exception thrown despite lobby not allocated');
        } catch (LobbyNotAllocatedException $e) {
            Assert::assertEquals('The lobby with the given ID has not been allocated.', $e->getMessage());
        }
    }

    /**
     * @param  string[]  $combinations
     * @return string[]
     */
    protected function generateAllIdsOfLength(
        int $length,
        array $combinations = [],
    ): array {
        $chars = range('A', 'Z');

        /**
         * If it's the first iteration, the first set of combinations
         * is the same as the set of characters.
         */
        if (count($combinations) === 0) {
            $combinations = $chars;
        }

        /**
         * We're done if we're at length 1.
         */
        if ($length == 1) {
            return $combinations;
        }

        /**
         * Initialise array to put new values in.
         */
        $new_combinations = [];

        /**
         * Loop through existing combinations and character set to create strings.
         */
        foreach ($combinations as $combination) {
            foreach ($chars as $char) {
                $new_combinations[] = $combination.$char;
            }
        }

        /**
         * Call same function again for the next iteration.
         */
        return $this->generateAllIdsOfLength($length - 1, $new_combinations);
    }
}
