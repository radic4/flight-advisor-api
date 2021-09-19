<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AirportRepositoryInterface;
use App\Models\Airport;

class AirportRepository implements AirportRepositoryInterface
{
    public function existsById($id)
    {
        return Airport::where('id', $id)->exists();
    }

    public function chunkInsert($array)
    {
        Airport::insertOrIgnore($array);
    }

    public function chunk($count, $callback)
    {
        Airport::where('synced', 0)->chunkById($count, $callback);
    }
}