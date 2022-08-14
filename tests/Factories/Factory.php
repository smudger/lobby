<?php

namespace Tests\Factories;

use Faker\Generator;

abstract class Factory
{
    protected Generator $faker;

    protected string $model;

    public function __construct(private int $count = 1)
    {
        $this->faker = \Faker\Factory::create();
    }

    public function count(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    /** @phpstan-ignore-next-line  */
    public function create(...$overrides): mixed
    {
        return $this->count === 1
            ? $this->createOne($overrides)
            : $this->createMany($overrides);
    }

    /** @return mixed[] */
    abstract protected function definition(): array;

    /** @param  mixed[]  $overrides */
    private function createOne(array $overrides): mixed
    {
        return new $this->model(...array_merge($this->definition(), $overrides));
    }

    /**
     * @param  mixed[]  $overrides
     * @return mixed[]
     */
    private function createMany(array $overrides): array
    {
        return array_map(fn () => $this->createOne($overrides), array_fill(0, $this->count, null));
    }
}
