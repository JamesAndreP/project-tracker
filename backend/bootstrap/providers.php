<?php

use App\Providers\AppServiceProvider;
use App\Providers\RepositoryServiceProvider;
use Laravel\Passport\PassportServiceProvider;
use Laravel\Passport\RouteServiceProvider;

return [
    AppServiceProvider::class,
    RepositoryServiceProvider::class,
    PassportServiceProvider::class,
    RouteServiceProvider::class,
];
