<?php

namespace App\Services;

use App\Repositories\Interfaces\Neo4jRepositoryInterface;
use App\Services\Interfaces\Neo4jServiceInterface;

class Neo4jService implements Neo4jServiceInterface
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
    }
    
    public function insertRoutes($routes)
    {
        $this->neo4jRepository->insertRoutes($routes);
    }

    public function findCheapestFlight($from, $to)
    {
        return $this->neo4jRepository->findCheapestFlight($from, $to);
    }
}