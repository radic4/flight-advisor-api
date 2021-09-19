<?php

namespace App\Repositories\NEO4J;

use App\Repositories\Interfaces\Neo4jRepositoryInterface;
use \Laudis\Neo4j\ClientBuilder;
use \Laudis\Neo4j\Contracts\TransactionInterface;

class Neo4jRepository implements Neo4jRepositoryInterface
{
    private $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
                ->withDriver('bolt', 'bolt://'.config('database.connections.neo4j.database').':'.config('database.connections.neo4j.password').'@'.config('database.connections.neo4j.host').':'.config('database.connections.neo4j.port').'')
                ->build();
    }

    public function addUniqueConstraint($name, $label, $column)
    {
        $this->client->writeTransaction(static function (TransactionInterface $tsx) use ($name, $label, $column) {
            $tsx->run("CREATE CONSTRAINT {$name} IF NOT EXISTS ON (a:{$label}) ASSERT a.{$column} IS UNIQUE");
            return true;
        });
        ;
    }

    public function insertAirports($airports)
    {
        $this->client->writeTransaction(static function (TransactionInterface $tsx) use ($airports) {
            $create = "CREATE ";
            foreach ($airports as $airport) {
                $create .= "(airport$airport->id:Airport {id: $airport->id, cityId: $airport->city_id}),";
            }
            $create = trim($create, ',');
            $tsx->run($create);
            return true;
        });
    }
    
    public function insertRoutes($routes)
    {
        $this->client->writeTransaction(static function (TransactionInterface $tsx) use ($routes) {
            $flights = "[";
            foreach ($routes as $route) {
                $flights .= "{from:$route->source_airport_id,to:$route->destination_airport_id,price:$route->price},";
            }
            $flights = trim($flights, ',');
            $flights .= "]";
            $flights = "UNWIND $flights as flight MATCH (f:Airport {id: flight.from}),(t:Airport {id: flight.to}) CREATE (f)-[r:FLIGHT {price: flight.price}]->(t)";
            $tsx->run($flights);
            return true;
        });
    }

    public function findCheapestFlight($from, $to)
    {
        return $this->client->readTransaction(static function (TransactionInterface $tsx) use ($from, $to) {
            return $tsx->run("MATCH (from:Airport {cityId: $from}), (to:Airport {cityId: $to}) CALL apoc.algo.dijkstra(from, to, 'FLIGHT>', 'price') YIELD path, weight RETURN nodes(path) AS nodes, weight")->toArray();
        });
    }
}