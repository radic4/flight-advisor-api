<?php

namespace App\Services;

use App\Services\Interfaces\FlightServiceInterface;
use App\Repositories\Interfaces\RouteRepositoryInterface;
use App\Repositories\Interfaces\Neo4jRepositoryInterface;

class FlightService implements FlightServiceInterface
{
    protected $routeRepository, $neo4jRepository;

    public function __construct(RouteRepositoryInterface $routeRepository, Neo4jRepositoryInterface $neo4jRepository)
    {
        $this->routeRepository = $routeRepository;
        $this->neo4jRepository = $neo4jRepository;
    }

    public function findCheapest($from, $to)
    {
        $cheapestFlightNeo = $this->neo4jRepository->findCheapestFlight($from, $to);
        if(!count($cheapestFlightNeo)) return ['message' => '0 flights found.'];
        
        $cheapestFlightNeo = $cheapestFlightNeo[0];
        $total = $cheapestFlightNeo->get('weight');
        $airports = $cheapestFlightNeo->get('nodes');
        
        $from = null;
        $to = null;
        $routes = [];
        foreach ($airports as $airport) {
            if(!$from) {
                $from = $airport->id;
                continue;
            }
            
            $from = $to ? $to : $from;
            $to = $airport->id;

            $routes[] = $this->routeRepository->findCheapestRoute($from, $to);
        }

        $response = [];
        foreach ($routes as $route) {
            $response['routes'][] = ['from' => $route->source->city->name, 'to' => $route->destination->city->name, 'price' => $route->price];
        }
        $response['total'] = number_format($total, 2);

        return $response;
    }
}