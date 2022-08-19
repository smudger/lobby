<?php

namespace Tests\Unit;

use App\Application\CreateLobbyCommand;
use App\Application\CreateLobbyHandler;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateLobbyControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->spy(CreateLobbyHandler::class);
    }

    /** @test */
    public function it_provides_the_request_body_to_the_handler(): void
    {
        $handler = $this->spy(CreateLobbyHandler::class);

        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $this->post('/lobbies', [
            'member_name' => 'Ayesha Nicole',
        ]);

        $handler->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (CreateLobbyCommand $command) => $command->member_name === 'Ayesha Nicole');
    }

    /** @test */
    public function the_member_name_is_required(): void
    {
        $response = $this->post('/lobbies', []);

        $response->assertSessionHasErrors([
            'member_name' => 'The name field is required.',
        ]);
    }

    /** @test */
    public function the_member_name_must_be_a_string(): void
    {
        $response = $this->post('/lobbies', [
            'member_name' => ['not a string'],
        ]);

        $response->assertSessionHasErrors([
            'member_name' => 'The name must be a string.',
        ]);
    }

    /** @test */
    public function the_member_name_must_not_be_greater_than_100_characters(): void
    {
        $response = $this->post('/lobbies', [
            'member_name' => Str::random(101),
        ]);

        $response->assertSessionHasErrors([
            'member_name' => 'The name must not be greater than 100 characters.',
        ]);
    }
}
