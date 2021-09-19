<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\AirportRepositoryInterface;
use Cache;

class AirportRepository implements AirportRepositoryInterface
{
    protected $airportRepository;

    public function __construct(AirportRepositoryInterface $airportRepository)
    {
        $this->airportRepository = $airportRepository;
    }

    public function existsById($id)
    {
        return Cache::tags(['airports'])->remember("airportExists-{$id}", 600, function() use ($id) {
            return $this->airportRepository->existsById($id);
        });
    }

    public function chunkInsert($array)
    {
        $this->airportRepository->chunkInsert($array);
        Cache::tags(['airports'])->flush();
    }

    public function chunk($count, $callback)
    {
        $this->airportRepository->chunk($count, $callback);
    }
}