<?php

namespace Tests\Unit;

use App\Application\CreateMemberCommand;
use App\Application\CreateMemberHandler;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Models\Lobby;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Auth\HasSession;
use App\Infrastructure\Auth\UserFactory;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use Illuminate\Support\Str;
use Mockery;
use Tests\Fakes\FakeUserFactory;
use Tests\TestCase;

class CreateMemberControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->spy(CreateMemberHandler::class);
        $this->app->bind(UserFactory::class, FakeUserFactory::class);
    }

    /** @test */
    public function it_provides_the_request_body_to_the_handler(): void
    {
        $handler = $this->spy(CreateMemberHandler::class);

        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $lobby = new Lobby($repository->allocate());

        $this->post('/members', [
            'lobby_id' => $lobby->id->__toString(),
            'name' => 'Ayesha Nicole',
        ]);

        $handler->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (CreateMemberCommand $command) => $command->lobby_id === $lobby->id->__toString()
            && $command->name === 'Ayesha Nicole');
    }

    /** @test */
    public function it_logs_in_the_created_member(): void
    {
        $user = Mockery::spy(HasSession::class);

        $userFactory = Mockery::mock(UserFactory::class);
        $userFactory->shouldReceive('createFromRaw')
            ->once()
            ->andReturn($user);
        $this->app->instance(UserFactory::class, $userFactory);

        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $lobby = new Lobby($repository->allocate());

        $this->post('/members', [
            'lobby_id' => $lobby->id->__toString(),
            'name' => 'Ayesha Nicole',
        ]);

        $user->shouldHaveReceived('login')
            ->once();
    }

    /** @test */
    public function it_returns_a_validation_error_if_the_lobby_is_not_allocated(): void
    {
        $this->mock(CreateMemberHandler::class, function ($mock) {
            $mock->shouldReceive('execute')
                ->andThrow(new LobbyNotAllocatedException());
        });

        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $response = $this->post('/members', [
            'lobby_id' => 'AAAA',
            'name' => 'Ayesha Nicole',
        ]);

        $response->assertSessionHasErrors([
            'lobby_id' => 'The selected lobby code is invalid.',
        ]);
    }

    /** @test */
    public function it_returns_a_validation_error_if_the_handler_throws_a_validation_error(): void
    {
        $this->mock(CreateMemberHandler::class, function ($mock) {
            $mock->shouldReceive('execute')
                ->andThrow(new ValidationException([
                    'name' => ['The name is wrong.'],
                ]));
        });

        $repository = new InMemoryLobbyRepository();
        $this->app->instance(LobbyRepository::class, $repository);

        $response = $this->post('/members', [
            'lobby_id' => 'AAAA',
            'name' => 'Ayesha Nicole',
        ]);

        $response->assertSessionHasErrors('name', 'The name is wrong.');
    }

    /** @test */
    public function the_lobby_id_is_transformed_to_uppercase(): void
    {
        $response = $this->post('/members', [
            'lobby_id' => 'aaaa',
            'name' => 'Ayesha Nicole',
        ]);

        $response->assertRedirect(route('lobby.show', ['id' => 'AAAA']));
    }

    /** @test */
    public function the_lobby_id_is_required(): void
    {
        $response = $this->post('/members', [
            'name' => 'Ayesha Nicole',
        ]);

        $response->assertSessionHasErrors([
            'lobby_id' => 'The lobby code field is required.',
        ]);
    }

    /** @test */
    public function the_lobby_id_must_be_a_string(): void
    {
        $response = $this->post('/members', [
            'lobby_id' => ['not a string'],
            'name' => 'Ayesha Nicole',
        ]);

        $response->assertSessionHasErrors([
            'lobby_id' => 'The lobby code must be a string.',
        ]);
    }

    /** @test */
    public function the_lobby_id_must_be_4_characters(): void
    {
        $response = $this->post('/members', [
            'lobby_id' => 'AAA',
            'name' => 'Ayesha Nicole',
        ]);

        $response->assertSessionHasErrors([
            'lobby_id' => 'The lobby code must be 4 characters.',
        ]);

        $response = $this->post('/members', [
            'lobby_id' => 'AAAAA',
            'name' => 'Ayesha Nicole',
        ]);

        $response->assertSessionHasErrors([
            'lobby_id' => 'The lobby code must be 4 characters.',
        ]);
    }

    /** @test */
    public function the_name_is_required(): void
    {
        $response = $this->post('/members', [
            'lobby_id' => 'AAAA',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
        ]);
    }

    /** @test */
    public function the_name_must_be_a_string(): void
    {
        $response = $this->post('/members', [
            'lobby_id' => 'AAAA',
            'name' => ['not a string'],
        ]);

        $response->assertSessionHasErrors([
            'name' => 'The name must be a string.',
        ]);
    }

    /** @test */
    public function the_name_must_not_be_greater_than_100_characters(): void
    {
        $response = $this->post('/members', [
            'lobby_id' => 'AAAA',
            'name' => Str::random(101),
        ]);

        $response->assertSessionHasErrors([
            'name' => 'The name must not be greater than 100 characters.',
        ]);
    }
}
