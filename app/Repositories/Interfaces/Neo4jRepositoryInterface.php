<?php

namespace App\Repositories\Interfaces;

interface Neo4jRepositoryInterface
{
    public function addUniqueConstraint($name, $label, $column);

    public function insertAirports($airports);
    
    public function insertRoutes($routes);
    
    public function findCheapestFlight($from, $to);
}