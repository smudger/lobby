<?php

namespace Tests\Factories;

use App\Domain\Models\Lobby;

class LobbyFactory extends Factory
{
    protected string $model = Lobby::class;

    private MemberFactory $memberFactory;

    public function __construct()
    {
        parent::__construct();

        $this->memberFactory = new MemberFactory();
    }

    protected function definition(): array
    {
        return [
            'members' => $this->memberFactory->count(3)->create(),
        ];
    }
}
