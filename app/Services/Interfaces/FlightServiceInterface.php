<?php

namespace App\Services\Interfaces;

interface FlightServiceInterface
{
    public function findCheapest($from, $to);
}