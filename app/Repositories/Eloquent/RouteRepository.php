<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RouteRepositoryInterface;
use App\Models\Route;

class RouteRepository implements RouteRepositoryInterface
{
    public function chunkInsert($array)
    {
        Route::insertOrIgnore($array);
    }

    public function chunk($count, $callback)
    {
        Route::where('synced', 0)->chunkById($count, $callback);
    }

    public function findCheapestRoute($from, $to)
    {
        return Route::with(['source', 'destination', 'source.city', 'destination.city'])->where('synced', 1)->where('source_airport_id', $from)->where('destination_airport_id', $to)->orderBy('price')->first();
    }
}