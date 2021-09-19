<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Models\City;

class CityRepository implements CityRepositoryInterface
{
    public function store($name, $country, $description)
    {
        return City::firstOrCreate([
            'name' => $name,
            'country' => $country,
        ], [
            'description' => $description,
        ]);
    }

    public function getSearchedCitiesWithComments($search, $comments)
    {
        $cities = $search ? City::search('name', $search) : City::query();

        if(is_null($comments)) return $cities->with('comments')->get();

        return $cities->with(['comments' => function($q) use ($comments) {
            $q->limit($comments);
        }])->get();
    }

    public function getCityByNameAndCountry($name, $country)
    {
        return City::where('name', $name)->where('country', $country)->first();
    }
}