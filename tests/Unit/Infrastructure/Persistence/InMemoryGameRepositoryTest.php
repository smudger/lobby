<?php

namespace Tests\Unit\Infrastructure\Persistence;

use App\Domain\Repositories\GameRepository;
use App\Infrastructure\Persistence\InMemoryGameRepository;
use PHPUnit\Framework\TestCase;

class InMemoryGameRepositoryTest extends TestCase
{
    use GameRepositoryTestTrait;

    protected function getRepository(): GameRepository
    {
        return new InMemoryGameRepository();
    }
}
