<?php

namespace App\Services\Interfaces;

interface CityServiceInterface
{
    public function store($name, $country, $description);
    
    public function getSearchedCitiesWithComments($search, $comments);

    public function getCityIdByNameAndCountry($name, $country);
}