<?php

namespace App\Repositories\Interfaces;

interface RouteRepositoryInterface
{
    public function chunkInsert($array);

    public function findCheapestRoute($from, $to);
}