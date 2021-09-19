<?php

namespace App\Services\Interfaces;

interface RouteServiceInterface
{
    public function saveFile($file);

    public function chunkInsert($array);

    public function chunk($count, $callback);
}