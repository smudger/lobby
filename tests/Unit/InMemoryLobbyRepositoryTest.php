<?php

namespace Tests\Unit;

use App\Domain\Exceptions\IdGenerationException;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use PHPUnit\Framework\Assert;

class InMemoryLobbyRepositoryTest extends LobbyRepositoryTest
{
    protected function getRepository(): LobbyRepository
    {
        return new InMemoryLobbyRepository();
    }

    /** @test */
    public function it_throws_an_exception_if_there_are_no_more_ids_available(): void
    {
        $repository = new InMemoryLobbyRepository($this->generateAllIds());

        try {
            $repository->nextId();

            Assert::fail('No exception thrown despite generation failure.');
        } catch (IdGenerationException $e) {
            Assert::assertEquals('No more lobby IDs available.', $e->getMessage());
        }
    }

    /**
     * @param  string[]  $combinations
     * @return string[]
     */
    public function generateAllIds(
        int $length = 4,
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
        return $this->generateAllIds($length - 1, $new_combinations);
    }
}
