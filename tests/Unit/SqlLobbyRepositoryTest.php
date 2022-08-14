<?php

namespace Tests\Unit;

use App\Domain\Exceptions\NoMoreLobbiesException;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Persistence\SqlLobbyRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class SqlLobbyRepositoryTest extends TestCase
{
    use LobbyRepositoryTest, RefreshDatabase;

    protected function getRepository(): LobbyRepository
    {
        return new SqlLobbyRepository();
    }

    /** @test */
    public function it_throws_an_exception_if_there_are_no_more_lobbies_available(): void
    {
        $firstLetterGroups = $this->generateAllIdsOfLength(2);
        $secondLetterGroups = $this->generateAllIdsOfLength(2);

        foreach ($firstLetterGroups as $firstLetterGroup) {
            $rows = collect($secondLetterGroups)
                ->map(fn (string $secondLetterGroup) => [
                    'id' => $firstLetterGroup.$secondLetterGroup,
                    'allocated_at' => now(),
                    'members' => json_encode([]),
                ])
                ->toArray();

            DB::table('lobbies')->insert($rows);
        }

        try {
            $this->getRepository()->allocate();

            Assert::fail('No exception thrown despite no more lobbies available.');
        } catch (NoMoreLobbiesException $e) {
            Assert::assertEquals('There are currently no lobbies available for allocation.', $e->getMessage());
        }
    }
}
