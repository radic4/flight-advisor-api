<?php

namespace App\Services\Interfaces;

interface Neo4jServiceInterface
{
    public function addUniqueConstraint($name, $label, $column);
    
    public function insertAirports($airports);
    
    public function insertRoutes($routes);

    public function findCheapestFlight($from, $to);
}