<?php

namespace App\Providers;

use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\AirportRepositoryInterface;
use App\Repositories\Interfaces\RouteRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\Neo4jRepositoryInterface;
use App\Repositories\Cache\CityRepository as CacheCityRepository;
use App\Repositories\Cache\CommentRepository as CacheCommentRepository;
use App\Repositories\Cache\AirportRepository as CacheAirportRepository;
use App\Repositories\Cache\RouteRepository as CacheRouteRepository;
use App\Repositories\Cache\Neo4jRepository as CacheNeo4jRepository;
use App\Repositories\Eloquent\CityRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\RouteRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\NEO4J\Neo4jRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CityRepositoryInterface::class, function ($app) {
            return new CacheCityRepository(
                new CityRepository
            );
        });

        $this->app->bind(CommentRepositoryInterface::class, function ($app) {
            return new CacheCommentRepository(
                new CommentRepository
            );
        });

        $this->app->bind(AirportRepositoryInterface::class, function ($app) {
            return new CacheAirportRepository(
                new AirportRepository
            );
        });
        
        $this->app->bind(RouteRepositoryInterface::class, function ($app) {
            return new CacheRouteRepository(
                new RouteRepository
            );
        });

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        $this->app->bind(Neo4jRepositoryInterface::class, function ($app) {
            return new CacheNeo4jRepository(
                new Neo4jRepository
            );
        });
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
