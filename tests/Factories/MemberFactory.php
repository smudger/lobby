<?php

namespace Tests\Factories;

use App\Domain\Models\Member;

class MemberFactory extends Factory
{
    protected string $model = Member::class;

    protected function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 100),
            'name' => $this->faker->name(),
        ];
    }
}
