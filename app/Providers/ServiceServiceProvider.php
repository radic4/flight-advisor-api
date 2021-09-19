<?php

namespace App\Providers;

use App\Services\Interfaces\CityServiceInterface;
use App\Services\Interfaces\CommentServiceInterface;
use App\Services\Interfaces\AirportServiceInterface;
use App\Services\Interfaces\RouteServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\Interfaces\Neo4jServiceInterface;
use App\Services\Interfaces\FlightServiceInterface;
use App\Services\CityService;
use App\Services\CommentService;
use App\Services\AirportService;
use App\Services\RouteService;
use App\Services\UserService;
use App\Services\Neo4jService;
use App\Services\FlightService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CityServiceInterface::class, CityService::class);
        $this->app->bind(CommentServiceInterface::class, CommentService::class);
        $this->app->bind(AirportServiceInterface::class, AirportService::class);
        $this->app->bind(RouteServiceInterface::class, RouteService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(Neo4jServiceInterface::class, Neo4jService::class);
        $this->app->bind(FlightServiceInterface::class, FlightService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
