<?php

namespace App\Services\Interfaces;

interface AirportServiceInterface
{
    public function saveFile($file);
    
    public function existsById($id);

    public function chunkInsert($array);

    public function chunk($count, $callback);
}