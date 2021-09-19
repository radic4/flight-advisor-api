<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\RouteRepositoryInterface;
use Cache;

class RouteRepository implements RouteRepositoryInterface
{
    protected $routeRepository;

    public function __construct(RouteRepositoryInterface $routeRepository)
    {
        $this->routeRepository = $routeRepository;
    }

    public function chunkInsert($array)
    {
        return $this->routeRepository->chunkInsert($array);
    }

    public function chunk($count, $callback)
    {
        $this->routeRepository->chunk($count, $callback);
        Cache::tags(['routes'])->flush();
    }

    public function findCheapestRoute($from, $to)
    {
        return Cache::tags(['routes'])->remember("route-{$from}-{$to}", 600, function() use ($from, $to) {
            return $this->routeRepository->findCheapestRoute($from, $to);
        });
    }
}