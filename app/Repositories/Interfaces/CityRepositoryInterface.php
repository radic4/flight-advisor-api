<?php

namespace App\Repositories\Interfaces;

interface CityRepositoryInterface
{
    public function store($name, $country, $description);
    
    public function getSearchedCitiesWithComments($search, $comments);

    public function getCityByNameAndCountry($name, $country);
}