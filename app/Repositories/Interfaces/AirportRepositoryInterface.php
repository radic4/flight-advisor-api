<?php

namespace App\Repositories\Interfaces;

interface AirportRepositoryInterface
{
    public function existsById($id);

    public function chunkInsert($array);
}