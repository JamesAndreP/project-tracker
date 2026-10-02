<?php

namespace App\Providers;

use App\Interfaces\AuthServiceInterface;
use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\ProjectServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\ProjectService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(ProjectServiceInterface::class, ProjectService::class);
    }

    public function boot(): void
    {
        //
    }
}
