<?php

namespace App\Infrastructure\Providers;

use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Auth\User;
use App\Infrastructure\Auth\UserFactory;
use App\Infrastructure\Persistence\SqlLobbyRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** @var string[] */
    public array $bindings = [
        LobbyRepository::class => SqlLobbyRepository::class,
        UserFactory::class => User::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
