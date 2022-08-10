<?php

namespace Tests\Unit;

use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Models\LobbyId;
use App\Domain\Repositories\LobbyRepository;
use PHPUnit\Framework\Assert;

trait LobbyRepositoryTest
{
    abstract protected function getRepository(): LobbyRepository;

    abstract public function it_throws_an_exception_if_there_are_no_more_lobbies_available(): void;

    /** @test */
    public function it_doesnt_return_a_lobby_that_has_already_been_allocated(): void
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
    public function it_can_allocate_a_lobby(): void
    {
        $repository = $this->getRepository();

        $lobby = $repository->allocate();

        Assert::assertTrue($lobby->equals($repository->findById($lobby->id)));
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
