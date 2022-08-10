<?php

namespace Tests\Unit;

use App\Domain\Exceptions\IdGenerationException;
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
    public function it_throws_an_exception_if_there_are_no_more_ids_available(): void
    {
        DB::table('lobbies')->update(['allocated_at' => now()]);

        try {
            $this->getRepository()->nextId();

            Assert::fail('No exception thrown despite generation failure.');
        } catch (IdGenerationException $e) {
            Assert::assertEquals('No more lobby IDs available.', $e->getMessage());
        }
    }
}
