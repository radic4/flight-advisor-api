<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\CityRepositoryInterface;
use Cache;
use Illuminate\Support\Str;

class CityRepository implements CityRepositoryInterface
{
    protected $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function store($name, $country, $description)
    {
        $result = $this->cityRepository->store($name, $country, $description);
        
        if($result->wasRecentlyCreated === true) {
            Cache::tags(['cities'])->flush();
            Cache::forget(Str::lower("cityByNameAndCountry-{$name}-{$country}"));
        }
        
        return $result;
    }

    public function getSearchedCitiesWithComments($search, $comments)
    {
        return Cache::tags(['cities'])->remember(Str::lower("cities-{$search}-{$comments}"), 600, function() use ($search, $comments) {
            return $this->cityRepository->getSearchedCitiesWithComments($search, $comments);
        });
    }

    public function getCityByNameAndCountry($name, $country)
    {
        return Cache::remember(Str::lower("cityByNameAndCountry-{$name}-{$country}"), 600, function() use ($name, $country) {
            return $this->cityRepository->getCityByNameAndCountry($name, $country);
        });
    }
}