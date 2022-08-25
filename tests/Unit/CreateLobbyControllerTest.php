<?php

namespace Tests\Unit;

use App\Application\CreateLobbyCommand;
use App\Application\CreateLobbyHandler;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Models\LobbyId;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Auth\HasSession;
use App\Infrastructure\Auth\UserFactory;
use App\Infrastructure\Events\InMemoryEventStore;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use Illuminate\Support\Str;
use Mockery;
use Tests\Factories\LobbyFactory;
use Tests\Fakes\FakeUserFactory;
use Tests\TestCase;

class CreateLobbyControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->mock(CreateLobbyHandler::class, function ($mock) {
            $mock->shouldReceive('execute')
                ->andReturn((new LobbyFactory())->create(id: LobbyId::fromString('AAAA')));
        });
        $this->app->bind(UserFactory::class, FakeUserFactory::class);
    }

    /** @test */
    public function it_provides_the_request_body_to_the_handler(): void
    {
        $handler = $this->spy(CreateLobbyHandler::class);

        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());
        $this->app->instance(LobbyRepository::class, $repository);

        $this->post('/lobbies', [
            'member_name' => 'Ayesha Nicole',
        ]);

        $handler->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (CreateLobbyCommand $command) => $command->member_name === 'Ayesha Nicole');
    }

    /** @test */
    public function it_logs_in_the_initial_member(): void
    {
        $user = Mockery::spy(HasSession::class);

        $userFactory = Mockery::mock(UserFactory::class);

        $userFactory->shouldReceive('createFromLobbyMember')
            ->once()
            ->andReturn($user);
        $this->app->instance(UserFactory::class, $userFactory);

        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());
        $this->app->instance(LobbyRepository::class, $repository);

        $this->post('/lobbies', [
            'member_name' => 'Ayesha Nicole',
        ]);

        $user->shouldHaveReceived('login')
            ->once();
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

    /** @test */
    public function it_returns_a_validation_error_if_the_handler_throws_a_validation_error(): void
    {
        $this->mock(CreateLobbyHandler::class, function ($mock) {
            $mock->shouldReceive('execute')
                ->andThrow(new ValidationException([
                    'member_name' => ['The name is wrong.'],
                ]));
        });

        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());
        $this->app->instance(LobbyRepository::class, $repository);

        $response = $this->post('/lobbies', [
            'member_name' => 'Ayesha Nicole',
        ]);

        $response->assertSessionHasErrors('member_name', 'The name is wrong.');
    }
}
