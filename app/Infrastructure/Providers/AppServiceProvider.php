<?php

namespace App\Infrastructure\Providers;

use App\Domain\Events\EventStore;
use App\Domain\Repositories\GameRepository;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Auth\User;
use App\Infrastructure\Auth\UserFactory;
use App\Infrastructure\Auth\UserRepository;
use App\Infrastructure\Events\BroadcastEventStore;
use App\Infrastructure\Events\SqlEventStore;
use App\Infrastructure\Persistence\InMemoryGameRepository;
use App\Infrastructure\Persistence\SqlLobbyRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** @var string[] */
    public array $bindings = [
        LobbyRepository::class => SqlLobbyRepository::class,
        GameRepository::class => InMemoryGameRepository::class,
        UserFactory::class => User::class,
        UserRepository::class => User::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EventStore::class, fn () => new BroadcastEventStore(new SqlEventStore()));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
