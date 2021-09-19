<?php

namespace App\Services;

use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Services\Interfaces\CityServiceInterface;

class CityService implements CityServiceInterface
{
    protected $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function store($name, $country, $description)
    {
        $city = $this->cityRepository->store($name, $country, $description);
        $message = $city->wasRecentlyCreated === true ? 'City created.' : 'City already exists.';

        return ['message' => $message, 'city' => $city];
    }

    public function getSearchedCitiesWithComments($search, $comments)
    {
        return $this->cityRepository->getSearchedCitiesWithComments($search, $comments);
    }

    public function getCityIdByNameAndCountry($name, $country)
    {
        return $this->cityRepository->getCityByNameAndCountry($name, $country)->id ?? null;
    }
}