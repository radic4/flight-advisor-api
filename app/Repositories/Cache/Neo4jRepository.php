<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\Neo4jRepositoryInterface;
use Cache;

class Neo4jRepository implements Neo4jRepositoryInterface
{
    protected $neo4jRepository;

    public function __construct(Neo4jRepositoryInterface $neo4jRepository)
    {
        $this->neo4jRepository = $neo4jRepository;
    }

    public function addUniqueConstraint($name, $label, $column)
    {
        $this->neo4jRepository->addUniqueConstraint($name, $label, $column);
    }

    public function insertAirports($airports)
    {
        $this->neo4jRepository->insertAirports($airports);
        Cache::tags(['flights'])->flush();
    }
    
    public function insertRoutes($routes)
    {
        $this->neo4jRepository->insertRoutes($routes);
        Cache::tags(['flights'])->flush();
    }

    public function findCheapestFlight($from, $to)
    {
        return Cache::tags(['flights'])->remember("cheapest-flight-{$from}-{$to}", 600, function() use ($from, $to) {
            return $this->neo4jRepository->findCheapestFlight($from, $to);
        });
    }
}