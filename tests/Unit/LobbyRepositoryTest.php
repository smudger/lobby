<?php

namespace Tests\Unit;

use App\Domain\Exceptions\IdGenerationException;
use App\Domain\Exceptions\LobbyNotFoundException;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;
use App\Domain\Repositories\LobbyRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

abstract class LobbyRepositoryTest extends TestCase
{
    abstract protected function getRepository(): LobbyRepository;

    abstract public function it_throws_an_exception_if_there_are_no_more_ids_available(): void;

    /** @test */
    public function it_doesnt_return_an_id_that_has_already_been_taken(): void
    {
        $repository = $this->getRepository();

        $first = $repository->nextId();
        $second = $repository->nextId();
        $third = $repository->nextId();

        Assert::assertFalse($first->equals($second));
        Assert::assertFalse($first->equals($third));
        Assert::assertFalse($second->equals($third));
    }

    /** @test */
    public function it_can_save_and_return_a_lobby(): void
    {
        $repository = $this->getRepository();

        $lobby = new Lobby($repository->nextId());

        $repository->save($lobby);

        Assert::assertTrue($lobby->equals($repository->findById($lobby->id)));
    }

    /** @test */
    public function it_throws_an_exception_if_a_lobby_with_the_given_id_does_not_exist(): void
    {
        $repository = $this->getRepository();

        $lobby = new Lobby($repository->nextId());

        $repository->save($lobby);

        try {
            $repository->findById($repository->nextId());

            Assert::fail('No exception thrown despite no lobby found.');
        } catch (LobbyNotFoundException $e) {
            Assert::assertEquals('A lobby with the given ID could not be found.', $e->getMessage());
        }
    }
}
