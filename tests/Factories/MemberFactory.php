<?php

namespace Tests\Factories;

use App\Domain\Models\Member;

class MemberFactory extends Factory
{
    protected string $model = Member::class;

    protected function definition(): array
    {
        return [
            'socketId' => $this->faker->numerify('###.###'),
            'name' => $this->faker->name(),
        ];
    }
}
